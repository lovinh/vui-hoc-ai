<?php

namespace app;

use app\core\Route;
use app\core\db\DB;
use app\core\http_context\Request;
use app\core\http_context\Response;
use app\core\middleware\AuthMiddleware;
use app\core\middleware\BaseMiddleware;
use app\core\middleware\ParamsMiddleware;
use Throwable;
use Exception;
use Error;
use RuntimeException;

use function app\core\helper\app_path;

class App
{
    private $__controller;
    private $__action;
    private $__parameters;
    private $__middleware;
    private $__request;
    private $__db;

    public static $app;

    function __construct()
    {
        global $app_config;

        // Khởi tạo liên kết đến bản thân đối tượng App
        self::$app = $this;

        $this->__middleware = new BaseMiddleware();
        $this->__request = new Request();

        // Khởi tạo trình xử lý và bắt lỗi
        try {
            // Khởi tạo trình xử lý
            ob_start();
            $this->init($this->parse_url());
        } catch (Exception $ex) {
            // Bắt lỗi
            ob_clean();
            $err_code = $ex->getCode();
            if ($err_code != 404) {
                $err_code = 500;
            }
            $err_data = [];
            if ($app_config["debug_mode"]) {
                $err_data = [
                    "is_error" => false,
                    "type" => get_class($ex),
                    "message" => $ex->getMessage(),
                    "trace" => $ex->getTrace(),
                    "traceAsString" => $ex->getTraceAsString()
                ];
                $this->load_error("debug", $err_data);
            } else {
                $this->load_error($err_code, $err_data);
            }

            die();
        } catch (Throwable $err) {
            // Bắt lỗis
            // ob_clean();
            $err_code = $err->getCode();
            if ($err_code != 404) {
                $err_code = 500;
            }
            $err_data = [];
            if ($app_config["debug_mode"]) {
                $err_data = [
                    "is_error" => true,
                    "type" => get_class($err),
                    "file" => $err->getFile(),
                    "line" => $err->getLine(),
                    "message" => $err->getMessage(),
                    "trace" => $err->getTrace(),
                    "traceAsString" => $err->getTraceAsString()
                ];
                $this->load_error("debug", $err_data);
            } else {
                $this->load_error($err_code, $err_data);
            }
            die();
        } finally {
            ob_end_flush();
        }
    }

    /**
     * Hàm khởi tạo các thông tin từ URL đầu vào.
     * @param string $url_check URL đầu vào.
     */
    function init($url_check)
    {
        global $app_config;
        // Kiểm tra lớp DB có tồn tại không. Nếu có mới thực hiện khởi tạo đối tượng
        if (class_exists("app\core\db\DB")) {
            $db = new DB();
            $this->__db = $db->get_db();
        }
        // Xử lý middleware

        // Global middleware
        if (!empty($app_config["global_middleware"])) {
            foreach (array_reverse($app_config["global_middleware"]) as $class_name) {
                $this->__middleware->add(new $class_name());
            }
            $this->__middleware->run($this->__request);
        }

        // Xử lý route

        $routes_dir = scandir("app/routes");
        if (!empty($routes_dir)) {
            foreach ($routes_dir as $routes_file) {
                if (
                    $routes_file != '.' && $routes_file != ".." && file_exists('app/routes/' . $routes_file)
                ) {
                    require_once "app/routes/" . $routes_file;
                }
            }
        }

        $request_method = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];

        $handler = Route::handle($this->__request, $request_method);

        // Xử lý route middleware
        // $this->handle_route_middleware($this->__route->get_route_key());

        // App Service Provider
        $this->handle_app_service_provider();

        // Handler
        $this->handle($handler);
    }

    /**
     * Xuất lỗi thành một trang web dựa theo mã lỗi và dữ liệu truyền vào
     * @param string $name Mã lỗi. Mặc định lỗi được truyền có mã `500`.
     * @param array $data Dữ liệu truyền vào. Mặc định mảng là rỗng.
     */
    public function load_error($name = "500", $data = [])
    {
        require_once "app/errors/" . $name . ".php";
    }

    // Helper functions

    /**
     * Hàm truy xuất thông tin lớp từ URL.
     */
    function parse_url()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            $url = $_SERVER['PATH_INFO'];
        } else {
            $url = "/";
        }

        // $url_check = $this->extract_url($url);

        $url_check = $url;

        return $url_check;
    }
    function extract_url(string $url)
    {
        $url_array = array_values(array_filter(explode('/', $url)));

        // Kiểm tra xem đường dẫn có là file hay không
        $url_check = '';
        if (!empty($url_array)) {
            foreach ($url_array as $key => $url_item) {
                $url_check = strtolower($url_check) . ucfirst($url_item) . '/';
                $file_check = rtrim($url_check, '/');
                if (!empty($url_array[$key - 1])) {
                    unset($url_array[$key - 1]);
                }
                if (file_exists("app/controllers/" . $file_check . ".php")) {
                    $url_check = $file_check;
                    break;
                }
            }
            $url_array = array_values($url_array);
        } else {
            $url_check = $this->__controller;
        }

        // Xử lý controller
        if (!empty($url_array[0])) {
            $this->__controller = ucfirst($url_array[0]);
        } else {
            $this->__controller = ucfirst($this->__controller);
        }
        $this->__controller = "app\core\controller\\" . $this->__controller;


        // Xử lý action
        if (!empty($url_array[1])) {
            $this->__action = ucfirst($url_array[1]);
        } else {
            // placeholder for error handle
        }

        // Xử lý các tham số
        $this->__parameters = array_slice($url_array, 2);

        return $url_check;
    }

    private function handle_app_service_provider()
    {
        global $app_config;
        if (!empty($app_config["boot"])) {
            $service_providers = $app_config["boot"];
            foreach ($service_providers as $service_provider) {
                $service_provider_object = new $service_provider();
                if (!empty($this->__db)) {
                    $service_provider_object->set_db($this->__db);
                }
                $service_provider_object->boot();
            }
        }
    }

    private function handle($handler)
    {
        if (is_array($handler['handler'])) {
            $handler['handler'][0] = new $handler['handler'][0]();
        }
        // var_dump($handler['handler']());
        // echo '<pre>';
        // print_r(Route::$routes);
        // echo '</pre>';
        $response = call_user_func_array($handler['handler'], $handler['params']);
        // var_dump($response);

        try {
            if (!($response instanceof Response)) {
                if (is_array($response)) {
                    $response = (new Response())->json($response);
                } else {
                    $response = (new Response())->set_content(strval($response));
                }
            }
            $response->send();
        } catch (Error $er) {
            throw new Error("APP: Handle không trả về response thỏa mãn");
        }
    }


    // Deprecated
    /**
     * Cảnh báo: Đây là hàm sử dụng bởi phiên bản cũ.
     * @deprecated
     */
    private function execute_handler($url_check)
    {
        // Kiểm tra file controller có tồn tại không
        if (!file_exists("app/controllers/" . $url_check . ".php")) {
            throw new RuntimeException("FILE NOT FOUND: File controller '" . $this->__controller . "' không tồn tại. Bạn đã tạo fil controller này chưa?", 404);
        }

        require_once "app/controllers/" . $url_check . ".php";

        if (!class_exists($this->__controller)) {
            // Kiểm tra lớp controller có tồn tại không
            throw new RuntimeException("CLASS NOT FOUND: Lớp controller '" . $this->__controller . "' không tồn tại. Đảm bảo rằng bạn có định nghĩa lớp này.!", 404);
        } else if (!method_exists($this->__controller, $this->__action)) {
            // Kiểm tra xem method của controller có tồn tại hay không
            throw new RuntimeException("METHOD NOT FOUND: Phương thức '" . $this->__controller . "->" . $this->__action . "()' Không tồn tại. Có thể bạn chưa định nghĩa phương thức này, hoặc bạn đã ghi sai tên.", 404);
        } else {
            $this->__controller = new $this->__controller();
            if (!empty($this->__db)) {
                $this->__controller->db = $this->__db;
            }

            // Hàm thực thi 
            call_user_func_array([$this->__controller, $this->__action], $this->__parameters);
        }
    }
    /**
     * Cảnh báo: Đây là hàm sử dụng bởi phiên bản cũ.
     * @deprecated
     */
    private function handle_route_middleware($route_key)
    {
        global $app_config;
        $route_key = trim($route_key);
        if (!empty($app_config["route_middleware"])) {
            $route_middleware_arr = $app_config["route_middleware"];
            foreach ($route_middleware_arr as $key => $route_middleware_item) {
                if ($route_key != trim($key)) {
                    continue;
                }

                if (!file_exists("app/middlewares/$route_middleware_item.php")) {
                    throw new RuntimeException("APP FILE MIDDLEWARE NOT FOUND: Không tìm thấy file middleware có tên '$route_middleware_item.php'. Đảm bảo bạn đã khởi tạo file này trong thư mục 'app/middlewares'");
                }

                require_once "app/middlewares/$route_middleware_item.php";

                $route_middleware_class = "app\core\middleware\\" . $route_middleware_item;

                if (!class_exists($route_middleware_class)) {
                    throw new RuntimeException("APP MIDDLEWARE NOT FOUND: Không tìm thấy lớp middleware '$route_middleware_class'. Đảm bảo bạn đã định nghĩa lớp này trong file 'app/middlewares/$route_middleware_item.php' hoặc kiểm tra lại cách đặt tên lớp.");
                }
                $route_middleware_object = new $route_middleware_class();
                if (!empty($this->__db)) {
                    $route_middleware_object->set_db($this->__db);
                }
                $route_middleware_object->handle();
            }
        }
    }
    /**
     * Cảnh báo: Đây là hàm sử dụng bởi phiên bản cũ.
     * @deprecated
     */
    private function handle_global_middleware()
    {
        global $app_config;
        if (!empty($app_config["global_middleware"])) {
            $global_middleware_arr = $app_config["global_middleware"];
            foreach ($global_middleware_arr as $global_middleware_item) {
                if (!file_exists("app/middlewares/$global_middleware_item.php")) {
                    throw new RuntimeException("APP FILE MIDDLEWARE NOT FOUND: Không tìm thấy file middleware có tên '$global_middleware_item.php'. Đảm bảo bạn đã khởi tạo file này trong thư mục 'middlewares'");
                }
                require_once "app/middlewares/$global_middleware_item.php";

                $global_middleware_class = "app\core\middleware\\" . $global_middleware_item;

                if (!class_exists($global_middleware_class)) {
                    throw new RuntimeException("APP MIDDLEWARE NOT FOUND: Không tìm thấy lớp middleware '$global_middleware_class'. Đảm bảo bạn đã định nghĩa lớp này trong file 'app/middlewares/$global_middleware_item.php' hoặc kiểm tra lại cách đặt tên lớp.");
                }

                $global_middleware_object = new $global_middleware_class();
                if (!empty($this->__db)) {
                    $global_middleware_object->set_db($this->__db);
                }
                $global_middleware_object->handle();
            }
        }
    }
}
