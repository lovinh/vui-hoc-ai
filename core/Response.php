<?php

namespace app\core\http_context;

use app\core\Template;
use InvalidArgumentException;

class Response
{

    private $__content = null;
    private $__header = [];
    private $__header_replaced = true;
    private $__status_code = 200;

    public function set_content($content)
    {
        $this->__content = $content;
        return $this;
    }

    /**
     * Chuyển hướng phản hồi đến đường dẫn mới
     * @param string $uri định dạng tài nguyên hệ thống. Mặc định là chuỗi rỗng. Nếu không chỉ định, tự động chuyển hướng đến trang gốc.
     * @param int $code mã trạng thái phản hồi. Mặc định là 301.
     */
    public function redirect($uri = "", int $code = 301)
    {
        // Kiểm tra xem có http|https không
        if (preg_match("~^(http|https)~is", $uri)) {
            $url = $uri;
        } else {
            $url = _WEB_ROOT . '/' . $uri;
        }
        http_response_code($code);
        header("Location: " . $url);
    }

    /**
     * Chỉ định header cho http response
     * @param string $key Giá trị khóa của header
     * @param string $value Giá trị thực của header
     * @param bool $replace cho phép header ghi đè giá trị khóa cũ bằng giá trị ở khóa mới, hoặc ép header cho phép có nhiều kiểu trên cùng 1 khóa
     */
    public function header(string $key, string $value)
    {
        if (empty($key)) {
            throw new InvalidArgumentException("RESPONSE EMPTY KEY: Key của header không được để trống!");
        }
        if (empty($value)) {
            throw new InvalidArgumentException("RESPONSE EMPTY KEY: Value của header không được để trống!");
        }
        $this->__header[$key] = $value;
        return $this;
    }
    /**
     * Chỉ định header cho http response, chấp nhận đầu vào là một mảng
     * @param array $list Gồm một cặp khóa `Key => Value` chỉ định nội dung header.
     * @param bool $replace cho phép header ghi đè giá trị khóa cũ bằng giá trị ở khóa mới, hoặc ép header cho phép có nhiều kiểu trên cùng 1 khóa
     */
    public function header_from_list(array $list)
    {
        if (empty($list)) {
            throw new InvalidArgumentException("RESPONSE EMPTY LIST: List không được để trống!");
        }
        foreach ($list as $key => $value) {
            $this->__header[$key] = $value;
        }
        return $this;
    }
    /**
     * Chỉ định giá trị cookie cho header.
     * @param string $name Tên của cookie.
     * @param mixed $value Giá trị của cookie.
     * @param int $minutes Số phút tồn tại của cookie.
     */
    public function cookie(string $name, $value, int $minutes, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("RESPONSE COOKIE EMPTY NAME: Tên của cookie không được để trống!");
        }
        if ($minutes <= 0) {
            throw new InvalidArgumentException("RESPONSE COOKIE INVALID MINUTE: Thời gian của cookie không được là số âm hoặc bằng 0!");
        }
        setcookie($name, $value, $minutes, $path, $domain, $secure, $httponly);
        return $this;
    }

    public function without_cookie(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("RESPONSE COOKIE EMPTY NAME: Tên của cookie không được để trống!");
        }
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
        setcookie($name, "", time() - 3600);
        return $this;
    }

    public function json(array $data)
    {
        header("Content-Type: application/json");
        $this->__content = json_encode($data);
        return $this;
    }

    public function response_code(int $code)
    {
        $this->__status_code = $code;
        return $this;
    }

    public function header_replaced($replace = true)
    {
        $this->__header_replaced = $replace;
        return $this;
    }

    public function send()
    {
        http_response_code($this->__status_code);
        header(implode(";", $this->__header));
        // echo $this->__content;
        eval("?> " . $this->__content . " <?php");
    }
}
