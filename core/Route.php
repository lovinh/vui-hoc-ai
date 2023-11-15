<?php

namespace app\core;

use app\core\http_context\Request;
use app\core\http_context\Response;
use app\core\middleware\BaseMiddleware;
use ErrorException;
use Exception;
use InvalidArgumentException;

use function app\core\helper\url;
use function PHPSTORM_META\type;

class Route
{
    private $__route_key = null;

    public static $routes = [];

    public static $current_idx = 0;

    public static $mapping_name_idx = [];

    private static $fallback = null;

    private static $route_middleware = null;

    private static $group_idx = [];

    public static function get_full_url()
    {
        return url((!empty($_SERVER['PATH_INFO']) ? ltrim($_SERVER['PATH_INFO'], '/') : ""));
    }

    /**
     * Đăng ký route cho request có phương thức GET.
     * @param string $uri Đường dẫn tài nguyên của request
     * @param mixed $handler callable object. Trình xử lý khi route gặp request phù hợp.
     */
    public static function get(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "GET");
    }

    public static function post(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "POST");
    }

    public static function put(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "PUT");
    }

    public static function patch(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "PATCH");
    }

    public static function delete(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "DELETE");
    }

    public static function any(string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, "any");
    }

    public static function match(array $method, string $uri, $handler)
    {
        $params = self::parse_uri($uri);
        return self::set_route($uri, $handler, $params, $method);
    }

    public static function redirect(string $source_uri, string $destination_uri, $status_code = 302)
    {
        $handler = function () use ($destination_uri, $status_code) {
            http_response_code($status_code);
            header("Location: " . url(trim($destination_uri, '/')));
            exit;
        };
        return self::set_route($source_uri, $handler, [], "NULL");
    }

    /**
     * Route xử lý khi không tìm thấy bất kỳ route nào có thể xử lý được request. Lưu ý: Route này luôn phải được gọi cuối cùng trong dãy đăng ký route.
     * @param mixed $handle Hàm xử lý của route fallback khi không tìm thấy route nào thỏa mãn xử lý request.
     */
    public static function fallback($handler)
    {
        self::$fallback = $handler;
    }

    public static function name(string $name)
    {
        if (isset(self::$mapping_name_idx[$name])) {
            throw new InvalidArgumentException("INVALID ROUTE NAME: Tên route '$name' đã được định nghĩa cho route index '" . self::$mapping_name_idx[$name] . "'. Vui lòng chọn tên khác cho route này (Index hiện tại: " . self::$current_idx . ")");
        }
        self::$mapping_name_idx[$name] = self::$current_idx - 1;
        return new self();
    }

    public static function where(string $param_name, string $regex_pattern)
    {
        if (empty($param_name)) {
            throw new InvalidArgumentException("ROUTE INVALID PARAM NAME: Tên của param request không được để trống!");
        }
        if (empty($regex_pattern)) {
            throw new InvalidArgumentException("ROUTE INVALID REGEX PATTERN: Biểu thức chính quy không được để trống!");
        }
        if (empty(self::$routes[self::$current_idx - 1]["params"])) {
            return new self();
        }
        if (!array_key_exists($param_name, self::$routes[self::$current_idx - 1]["params"])) {
            throw new InvalidArgumentException("ROUTE INVALID PARAM NAME: Tên của param request không tồn tại!");
        }
        self::$routes[self::$current_idx - 1]["params"][$param_name] = $regex_pattern;
        return new self();
    }

    public static function where_in(string $param_name, array $item_list)
    {
        if (empty($param_name)) {
            throw new InvalidArgumentException("ROUTE INVALID PARAM NAME: Tên của param request không được để trống!");
        }
        if (empty($item_list)) {
            throw new InvalidArgumentException("ROUTE INVALID ITEM LIST: Danh sách phần tử không được để trống!");
        }
        if (empty(self::$routes[self::$current_idx - 1]["params"])) {
            return new self();
        }
        if (!array_key_exists($param_name, self::$routes[self::$current_idx - 1]["params"])) {
            throw new InvalidArgumentException("ROUTE INVALID PARAM NAME: Tên của param request không tồn tại!");
        }
        self::$routes[self::$current_idx - 1]["params"][$param_name] = implode('|', $item_list);
        return new self();
    }

    /**
     * Nhóm các route có các tính chất chung (Chung middleware, chung controller, ...) lại với nhau.
     * @param callable $group_handler hàm callable gồm các phương thức chỉ định route, được gọi ngay sau khi hàm group thực hiện.
     */
    public static function group(callable $group_handler)
    {
        $start_group_idx = self::$current_idx;
        call_user_func($group_handler);
        $end_group_idx = self::current_idx();
        for ($i = $start_group_idx; $i <= $end_group_idx; $i++) {
            array_push(self::$group_idx, $i);
        }
        return new self();
    }

    /**
     * Chỉ định lớp middleware sử dụng riêng cho route được chọn. Nếu đang chỉ có một route được chọn thì controller sẽ chỉ gắn cho riêng route đó. Nếu một nhóm route được chọn thì các middleware sẽ được gán cho tất cả các route có trong nhóm
     * @param string|array $middleware_class Tên lớp middleware chỉ định cho route đang được chọn. Chấp nhận đầu vào là một mảng.
     */
    public static function middleware(string|array $middleware_class, $params = [])
    {
        if (empty($middleware_class)) {
            throw new InvalidArgumentException("ROUTE INVALID MIDDLEWARE CLASS: Tên middlewares class không được để trống!");
        }
        if (!is_array($middleware_class)) {
            $middleware_class = array($middleware_class);
        }
        if (is_array($middleware_class) && empty($middleware_class)) {
            throw new InvalidArgumentException("ROUTE INVALID MIDDLEWARE CLASS: Mảng middlewares class không được để trống!");
        }
        $init_middleware_class = [];
        foreach ($middleware_class as $key => $value) {
            $init_middleware_class[$value] = new $value();
        }
        if (empty(self::$group_idx))
            self::$routes[self::$current_idx - 1]['middleware'] = $init_middleware_class;
        else {
            foreach (self::$group_idx as $idx) {
                self::$routes[$idx]['middleware'] = $init_middleware_class;
            }
            self::$group_idx = [];
        }
    }

    /**
     * Chỉ định controller sử dụng riêng cho route được chọn. Nếu đang chỉ có một route được chọn thì controller sẽ chỉ gắn cho riêng route đó. Nếu một nhóm route được chọn thì controller sẽ được gán cho tất cả các route có trong nhóm. Chú ý để gán được controller thì bắt buộc handler trong route được chọn không bao gồm controller khác, chỉ bao gồm method (Nếu có) và không phải closure.
     * @param string|array $controller_class Tên lớp controller chỉ định cho route đang được chọn. 
     */
    public function controller(string $controller_class)
    {
        if (empty($controller_class)) {
            throw new InvalidArgumentException("ROUTE INVALID CONTROLLER CLASS NAME: Tên lớp controller không được để trống!");
        }
        $add_controller = function ($idx, &$route) use ($controller_class) {

            if (is_array($route['handler'])) {
                if (count($route['handler']) > 1) {
                    throw new ErrorException("ROUTE DUPLICATE CONTROLLER CLASS: Route index '" . $idx . "' đã tồn tại controller '" . $route['handler'][0] . "'. Vui lòng kiểm tra lại!");
                }
                array_unshift($route['handler'], $controller_class);
            } else {
                if (is_callable($route['handler'])) {
                    throw new ErrorException("ROUTE CLOSURE FOUND: Không thể chỉ định controller cho route đã có sẵn handler là closure!");
                }
                $route['handler'] = array($controller_class, $route['handler']);
            }
        };
        if (empty(self::$group_idx)) {
            $add_controller(self::current_idx(), self::$routes[self::current_idx()]);
        } else {
            foreach (self::$group_idx as $idx) {
                $add_controller($idx, self::$routes[$idx]);
            }
            self::$group_idx = [];
        }
        return new self();
    }
    public static function get_params($route_name)
    {
        $id = $route_name;
        if (is_string($route_name)) {
            if (!array_key_exists($route_name, self::$mapping_name_idx)) {
                throw new InvalidArgumentException("ROUTE INVALID ROUTE_NAME: Không tồn tại route có tên '$route_name'!");
            }
            $id = self::$mapping_name_idx[$route_name];
        }
        if (!array_key_exists($id, self::$routes)) {
            throw new InvalidArgumentException("ROUTE INVALID ROUTE INDEX: Không tồn tại route có index '$id'!");
        }
        return self::$routes[$id];
    }

    // Handling method

    public static function handle(Request $request): array
    {
        $url = $request->path();
        $method = null;
        if ($request->is_get()) {
            $method = "GET";
        } else {
            $method = $request->get_fields_data()['_method'] ?? "POST";
        }
        self::$route_middleware = new BaseMiddleware();
        $setup = function ($route, $params) {
            $returned = [
                "handler" => null,
                "params" => null
            ];
            $returned['handler'] = $route["handler"];
            $returned['params'] = $params;
            return $returned;
        };
        // Duyệt các route được đăng ký
        foreach (self::$routes as $key => $route) {

            // Tìm kiếm uri match với uri đã đăng ký route
            $mapping_result = self::map_uri($url, $route['uri'], $route['params']);

            if (!$mapping_result['is_map'])
                continue;

            // Xử lý route middleware
            self::$route_middleware->clear();

            if (!empty($route['middleware'])) {
                foreach ($route['middleware'] as $middleware) {
                    self::$route_middleware->add(new $middleware(), $mapping_result['params']);
                }

                self::$route_middleware->run($request);
            }

            $params = $mapping_result['params'];

            if ($route['method'] == strtoupper($method)) {
                return $setup($route, $params);
            }
            if (is_array($route['method'])) {
                if (in_array(strtoupper($method), $route['method'])) {
                    return $setup($route, $params);
                }
            }
            if ($route['method'] == "ANY") {
                return $setup($route, $params);
            }
            if ($route['method'] == "NULL") {
                return $setup($route, $params);
            }
            continue;
        }

        if (empty(self::$fallback)) {
            self::abort();
        } else {
            $route = [
                "handler" => self::$fallback
            ];
            return $setup($route, []);
        }
    }

    // Helper method
    private static function current_idx()
    {
        return self::$current_idx - 1;
    }
    private static function map_uri(string $url, string $uri, $validated_params = [])
    {
        $exploded_uri = explode('/', $uri);
        $exploded_url = explode('/', $url);
        $returned = [
            "is_map" => true,
            "params" => []
        ];
        if (count($exploded_uri) != count($exploded_url)) {
            $returned["is_map"] = false;
            return $returned;
        }
        for ($i = 0; $i <= count($exploded_uri) - 1; $i++) {
            if (preg_match('~{\s*(.+?)\s*}~is', $exploded_uri[$i], $match) && !empty($exploded_url[$i])) {
                if (!empty($validated_params[$match[1]]) && !preg_match('~' . $validated_params[$match[1]] . '~s', $exploded_url[$i])) {
                    $returned["is_map"] = false;
                    return $returned;
                }
                continue;
            }
            if ($exploded_url[$i] != $exploded_uri[$i]) {
                $returned['is_map'] = false;
                break;
            }
        }
        if ($returned['is_map']) {
            foreach ($exploded_uri as $key => $value) {
                if (preg_match('~{\s*(.+?)\s*}~is', $value)) {
                    if (!empty($exploded_url[$key]))
                        array_push($returned['params'], $exploded_url[$key]);
                }
            }
        }
        return $returned;
    }

    private static function parse_uri($uri)
    {
        $params = [];
        $exploded_uri = explode('/', $uri);
        foreach ($exploded_uri as $key => $value) {
            if (preg_match('~{\s*(.+?)\s*}~is', $value, $match)) {
                $params[$match[1]] = null;
            }
        }
        return $params;
    }

    private static function set_route(string $uri, $handler, array $params, string|array $method)
    {
        $is_method_array = false;
        if (is_array($method)) {
            foreach ($method as $key => $value) {
                self::validate_allowed_method(strtoupper($value));
                $method[$key] = strtoupper($value);
            }
            $is_method_array = true;
        } else {
            self::validate_allowed_method(strtoupper($method));
        }
        self::$routes[self::$current_idx] = [
            "uri" => $uri,
            "handler" => $handler,
            "params" => $params,
            "method" => $is_method_array ? $method : strtoupper($method),
            "middleware" => null
        ];
        self::$current_idx += 1;
        return new self();
    }

    private static function abort(int $status_code = 404)
    {
        http_response_code($status_code);
        die();
    }

    // validate
    private static function validate_allowed_method(string $inp)
    {
        $validated_array = array("GET", "POST", "PUT", "PATCH", "DELETE", "ANY", "NULL");
        if (!in_array($inp, $validated_array, true)) {
            throw new InvalidArgumentException("ROUTE INVALID REQUEST METHOD: Request method '$inp' không hợp lệ! Giá trị hợp lệ bao gồm: " . '"GET", "POST", "PUT", "PATCH", "DELETE", "ANY"');
        }
    }

    // Deprecated function
    /**
     * @deprecated
     */
    public static function run($url)
    {
        // $url = trim($url, '/');
        if (!empty(self::$routes)) {
            foreach (self::$routes as $key => $value) {
                if (preg_match("~" . $value['uri'] . "~is", $url)) {
                    $url = preg_replace("~" . $value['uri'] . "~is", $value['handler'], $url);
                    break;
                }
            }
            return $url;
        }
    }

    /**
     * @deprecated
     */
    public function handle_route($url)
    {
        global $router;
        unset($router["default_controller"]);

        $url = trim($url, '/');

        $handling_url = $url;
        if (!empty($router)) {
            foreach ($router as $key => $value) {
                if (preg_match("~" . $key . "~is", $url)) {
                    $handling_url = preg_replace("~" . $key . "~is", $value, $url);
                    $this->__route_key = $key;
                }
            }
        }

        return $handling_url;
    }
}
