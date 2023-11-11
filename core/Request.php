<?php

namespace app\core\http_context;

use app\core\utils\FileUpload;
use ErrorException;

use function app\core\helper\url;

class Request
{
    // private $__rules = [];
    // private $__messages = [];
    public $validate;
    function __construct()
    {
        $this->validate = new Validator();
    }
    /**
     * Trả về loại phương thức yêu cầu (Request type).
     * @return string Trả về loại phương thức yêu cầu (Request type) dưới dạng lowercase.
     */
    public function get_method()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Kiểm tra phương thức có phải là `POST` hay không
     * @return bool Trả về `true` nếu phương thức hiện tại đang là POST. Ngược lại trả về `false`.
     */
    public function is_post()
    {
        if ($this->get_method() == 'post') {
            return true;
        }
        return false;
    }
    /**
     * Kiểm tra phương thức có phải là `GET` hay không
     * @return bool Trả về `true` nếu phương thức hiện tại đang là `GET`. Ngược lại trả về `false`.
     */
    public function is_get()
    {
        if ($this->get_method() == 'get') {
            return true;
        }
        return false;
    }
    /**
     * Lấy dữ liệu được request. Dữ liệu bao gồm tên trường (request field) và giá trị, được biểu diễn thành một mảng.
     * @return array Mảng gồm trường => giá trị. Là các dữ liệu được request.
     */
    public function get_fields_data()
    {
        $data_return = [];
        if ($this->is_get()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    if (is_array($value)) {
                        $data_return[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $data_return[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if ($this->is_post()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (is_array($value)) {
                        $data_return[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $data_return[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $value) {
                    $data_return[$key] = new FileUpload($key);
                }
            }
        }
        return $data_return;
    }

    /**
     * Trả về thông tin đường dẫn của request. Ví dụ: Nếu request có dạng `http://web.com/abc/xyz` thì `path()` trả về `abc/xyz`
     */
    public function path()
    {
        return $_SERVER['PATH_INFO'] ?? "/";
    }

    /**
     * Kiểm tra xem tiền tố đường dẫn có trùng với mẫu được chỉ định hay không. Ví dụ: Nếu mẫu chỉ định là `/home/*` thì tất cả các đường dẫn có tiền tố `/home/` như `/home/abc`, `/home/xyz/abc` đều được chấp nhận, các trường hợp khác sẽ không chấp nhận.
     * @param string $pattern mẫu sử dụng để kiểm tra đường dẫn. Sử dụng dấu `*` để đánh dấu phần cho phép là bất kỳ gì.
     * @return bool Trả về `true` nếu đường dẫn hợp lệ. Ngược lại trả về false.
     */
    public function like_path(string $pattern = "*")
    {
        $return = preg_match("~" . $pattern . "~i", $this->path());
        if ($return === false) {
            throw new ErrorException("REQUEST PATTERN ERROR: Mẫu không hợp lệ! Kiểm tra warning để biết thêm chi tiết!");
        }
        return $return == 1 ? true : false;
    }

    /**
     * Trả về chuỗi truy vấn của request.
     */
    public function query_string()
    {
        return $_SERVER["QUERY_STRING"] ?? "";
    }

    /**
     * Trả về url không chứa query string
     */
    public function url()
    {
        return url(ltrim($this->path(), '/'));
    }
    /**
     * Trả về url chứa query string
     */
    public function full_url()
    {
        return url(ltrim($this->path(), '/')) . (!empty($this->query_string()) ? '?' . $this->query_string() : false);
    }

    /**
     * Trả về full url hiện tại và bổ sung thêm các query được chỉ định
     * @param array $query_array Mảng gồm tên các query đi kèm giá trị của chúng
     */
    public function full_url_with_query(array $query_array)
    {
        $url = $this->full_url();
        foreach ($query_array as  $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        return $url;
    }

    /**
     * Trả về full url hiện tại và lọc các query có trong mảng được chỉ định
     * @param array $query_filter Mảng gồm tên các query cần phải lọc.
     */
    public function full_url_without_query(array $query_filter)
    {
        $query_string_array = explode('&', $this->query_string());
        foreach ($query_filter as $filter) {
            foreach ($query_string_array as $key => $query) {
                if (preg_match('~' . $filter . '~s', $query)) {
                    unset($query_string_array[$key]);
                }
            }
        }
        $url = $this->url();
        if (!empty($query_string_array))
            foreach ($query_string_array as $value) {
                $url .= '&' . $value;
            }
        return rtrim($url, '&');
    }

    /**
     * Trả về địa chỉ host của request
     */
    public function host()
    {
        return $_SERVER["HTTP_HOST"];
    }

    public function header(string $key = "", $default = null)
    {
        $header = getallheaders();
        if (empty($header) || $header === false) {
            return $default;
        }

        if (empty($key)) {
            return $header;
        }

        if (!array_key_exists($key, $header)) {
            return $default;
        }

        return $header[$key];
    }

    public function has_header(string $header_name)
    {
        $header = $this->header($header_name);
        if ($header == null) {
            return false;
        }
        return true;
    }

    public function get_acceptable_content_type()
    {
        $header = $this->header('Accept');
        if ($header == null) {
            return null;
        }
        return explode(',', $header);
    }

    public function cookie(string $name = '')
    {
        $header = $this->header('Cookie');
        if ($header == null) {
            return null;
        }
        $cookie_array = explode(';', $header);
        $cookie = [];
        foreach ($cookie_array as $item) {
            $cookie_item_array = explode('=', $item);
            $cookie[$cookie_item_array[0]] = $cookie_item_array[1];
        }
        return $cookie_item_array;
    }

    public function file(string $file_field_name = '')
    {
        return new FileUpload($file_field_name);
    }
}
