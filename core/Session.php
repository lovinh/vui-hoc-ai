<?php

namespace app\core;

use ValueError;

class Session
{
    /**
     * Thiết lập giá trị `key => value` cho session, hoặc trả về giá trị của một key nào đó của session.
     * @param object $key Giá trị của `key cho session
     * @param object $value Giá trị của `value` cho session. Mặc định là rỗng.
     * @return object|bool Trả về chuỗi nếu như `$value` rỗng và `$key` có tồn tại trong session.
     * 
     * Nếu `$value` không rỗng trả về kiểu bool: Trả về `true` nếu thiết lập giá trị `key => value` cho session thành công. Trả về `false` nếu thất bại (Không tìm thấy $key)
     */
    public static function data($key = '', $value = '')
    {
        $session_key = Session::validate();
        if (!empty($value)) {
            $_SESSION[$session_key][$key] = $value;
            return true;
        }

        if (empty($key)) {
            if (isset($_SESSION[$session_key])) {
                return $_SESSION[$session_key];
            }
        } else {
            if (isset($_SESSION[$session_key][$key])) {
                return $_SESSION[$session_key][$key];
            } else {
                trigger_error("SESSION KEY MISSING: Không tồn tại key '$key' trong session!", E_USER_WARNING);
            }
        }
        return false;
    }

    /**
     * Xóa session với key hoặc xóa toàn bộ session.
     * @param string $key Tên key đại diện cho session cần xóa. Mặc định key là rỗng. Nếu key rỗng, toàn bộ session bị xóa
     * @return bool Trả về `true` nếu thực hiện xóa thành công. Trả về `false` nếu xóa thất bại.
     */
    public static function delete($key = '')
    {
        $session_key = Session::validate();
        if (!empty($key)) {
            if (isset($_SESSION[$session_key][$key])) {
                unset($_SESSION[$session_key][$key]);
                return true;
            }
            return false;
        } else {
            unset($_SESSION[$session_key]);
            return true;
        }
        return false;
    }
    /**
     * Trả về tất cả các giá trị được lưu trữ trong session
     * @return array Mảng `key => value` được lưu trong session. Trả về `null` nếu không tồn tại. 
     */
    public static function all()
    {
        $session_key = Session::validate();
        if (isset($_SESSION[$session_key])) {
            return $_SESSION[$session_key];
        }
        return null;
    }

    /**
     * Trả về giá trị của session tại `key` khác null.
     * @param string $key Key của session cần lấy giá trị.
     * @param object $default Giá trị mặc định trả về nếu không tìm thấy key.
     * @return object Giá trị của session tại `key`. Trả về giá trị `default` nếu không tìm thấy
     */
    public static function get($key, $default = null)
    {
        $session_key = Session::validate();
        if (Session::has($key)) {
            return $_SESSION[$session_key][$key];
        }
        return $default;
    }

    /**
     * Kiếm tra xem phần tử có key `key` có tồn tại và không có giá trị `null` trong session
     * @param string $key key cần kiểm tra
     * @return bool Trả về `true` nếu phần tử có tồn tại và không có giá trị `null` trong session. Ngược lại trả về `false`.
     */
    public static function has($key)
    {
        $session_key = Session::validate();
        return isset($_SESSION[$session_key][$key]);
    }
    /**
     * Kiếm tra xem phần tử có key `key` có tồn tại giá trị khác rỗng  trong session hay không.
     * @param string $key key cần kiểm tra
     * @return bool Trả về `true` nếu phần tử có tồn tại giá trị khác rỗng trong session. Ngược lại trả về `false`.
     */
    public static function has_value($key)
    {
        $session_key = Session::validate();
        return !empty($_SESSION[$session_key][$key]);
    }

    /**
     * Kiếm tra xem phần tử có key `key` có tồn tại, kể cả có giá trị `null`, trong session.
     * @param string $key key cần kiểm tra
     * @return bool Trả về `true` nếu phần tử có tồn tại, kể cả có giá trị `null`, trong session. Ngược lại trả về `false`.
     */
    public static function exist($key)
    {
        $session_key = Session::validate();
        if (!isset($_SESSION[$session_key])) {
            return false;
        }
        return array_key_exists($key, $_SESSION[$session_key]);
    }

    /**
     * Thiết lập phần tử mới trong sessioncó `key` và `value` được chỉ định. Nếu có phần tử `key => value` đã tồn tại thì sẽ bị ghi đè bởi giá trị `value` mới.
     * @param string $key Giá trị `key` mới và khác rỗng.
     * @param object $value Giá trị `value` mới và khác rỗng.
     */
    public static function put($key, $value)
    {
        $session_key = Session::validate();
        if (empty($key)) {
            throw new ValueError("SESSION PUT EMPTY KEY: Không thể thiết lập một session có key rỗng");
        }
        $_SESSION[$session_key][$key] = $value;
    }

    /**
     * Đẩy thêm giá trị `value` mới vào phần tử có key `key` đã tồn tại trong session và có giá trị là một mảng.
     * @param string $key key của phần tử đã có trong session.
     * @param object $value Giá trị `value` mới được đẩy thêm vào phần value của phần tử. Có thể là một mảng giá trị.
     */
    public static function push($key, ...$value)
    {
        $session_key = Session::validate();
        if (!Session::exist($key)) {
            throw new ValueError("SESSION KEY MISSING: Không tìm thấy phần tử có key '$key'");
        }
        if (empty($value)) {
            throw new ValueError("SESSION EMPTY VALUE: Giá trị không được để trống!");
        }

        if (is_array($_SESSION[$session_key][$key])) {
            array_push($_SESSION[$session_key][$key], ...$value);
            return;
        }
        $new_array = array();
        array_push($new_array, ...$value);
        Session::put($key, $new_array);
    }

    /**
     * Xóa phần tử có key là `key` khỏi session và trả về phần tử đó.
     * @param string $key Key của phần tử cần xóa.
     * @param object $default Giá trị mặc định trả về nếu không tìm thấy key.
     * @return object|bool Giá trị của session tại `key`. Trả về giá trị `default` nếu không tìm thấy. Trả về `false` nếu xóa thất bại.
     */
    public static function pull($key, $default = null)
    {
        $session_key = Session::validate();
        if (!Session::has($key)) {
            return $default;
        }
        $value = Session::get($key);

        if (isset($_SESSION[$session_key]) && isset($_SESSION[$session_key][$key])) {
            Session::forget($key);
            return $value;
        }
        return false;
    }

    /**
     * Xóa phần tử có key là `key` khỏi session.
     * @param string $key Key của phần tử cần xóa.
     * @return bool Trả về `true` nếu xóa thành công. Trả về `false` nếu xóa thất bại.
     */
    public static function forget($key)
    {
        $session_key = Session::validate();
        if (empty($key)) {
            return false;
        }
        if (!isset($_SESSION[$session_key])) {
            return false;
        }
        if (!isset($_SESSION[$session_key][$key])) {
            return false;
        }
        unset($_SESSION[$session_key][$key]);
        return true;
    }

    /**
     * Xóa toàn bộ phần tử khỏi session.
     */
    public static function clear()
    {
        $session_key = Session::validate();
        if (isset($_SESSION[$session_key])) {
            unset($_SESSION[$session_key]);
            return true;
        }
        return false;
    }

    /**
     * Thiết lập một phần tử session chỉ tồn tại trong một lần request. Sau lần request, phần tử sẽ bị xóa khỏi session
     * @param string $key Giá trị `key` khác rỗng.
     * @param object $value Giá trị `value` khác rỗng.
     */
    public static function flash($key, $value = '')
    {
        if (!empty($value)) {
            Session::put($key, $value);
            return;
        }
        return Session::pull($key);
    }

    private static function validate()
    {
        global $session_config;
        if (empty($session_config)) {
            throw new ValueError("SESSION CONFIG NOT FOUND: Không tìm thấy cài đặt session config! Vui lòng kiểm tra lại!");
        }
        if (empty($session_config["session_key"])) {
            throw new ValueError("SESSION CONFIG MISSING KEY: Thiếu key của session config! Vui lòng kiểm tra lại!");
        }
        return $session_config['session_key'];
    }
}
