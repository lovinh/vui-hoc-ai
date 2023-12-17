<?php

namespace app\core\http_context;

use app\core\utils\FileUpload;
use RuntimeException;
use ValueError;
use InvalidArgumentException;
use DateTime;

trait DefinedRules
{
    private $__previous_unique_table = "";
    private $__previous_unique_field = "";
    // Validation function
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu không được để trống.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function required($message = "")
    {
        if (isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!empty($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải có tối thiểu bao nhiêu ký tự
     * @param int $value Số ký tự tối thiểu cần có. Mặc định bằng 0.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function min_char($value = 0, $message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if ((!is_int($value)) || $value < 0) {
            throw new RuntimeException("VALIDATOR ERROR: Giá trị phải là số nguyên không âm. Giá trị không phù hợp: '$value'");
        }
        if (strlen(trim($this->__fields_data[$this->__field_name])) >= $value) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ được có tối đa bao nhiêu ký tự
     * @param int $value Số ký tự tối đa được có
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function max_char($value, $message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if ((!is_int($value)) || $value < 0) {
            throw new RuntimeException("VALIDATOR ERROR: Giá trị phải là số nguyên không âm. Giá trị không phù hợp: '$value'");
        }
        if ((!is_int($value)) || $value < 0) {
            throw new RuntimeException("VALIDATOR ERROR: Giá trị phải là số nguyên không âm. Giá trị không phù hợp: '$value'");
        }
        if (strlen(trim($this->__fields_data[$this->__field_name])) <= $value) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải có định dạng email
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function email($message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (filter_var($this->__fields_data[$this->__field_name], FILTER_VALIDATE_EMAIL)) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải trùng với trường dữ liệu có tên được chỉ định
     * @param string $field_name Trường dữ liệu được chỉ định
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function match($field_name, $message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (trim($this->__fields_data[$this->__field_name] == trim($this->__fields_data[$field_name]))) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ bảo gồm các ký tự trong bảng chữ cái tiếng Anh
     * @param string $field_name Trường dữ liệu được chỉ định
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function is_alpha($message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (ctype_alpha($this->get_current_field_data()))
            return $this;
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ bảo gồm các chữ số.
     * @param string $field_name Trường dữ liệu được chỉ định
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function numeric($message = "")
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (ctype_digit($this->get_current_field_data()))
            return $this;
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn là ngày ngay sau ngày được chỉ định. Ngày tháng dạng chuỗi sẽ được truyền qua hàm `strtotime` để chuyển về dạng `DateTime`. 
     * @param string $date Ngày được chỉ định hoặc tên trường khác muốn được sử dụng để so sánh.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function after($date, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_date = null;
        if (array_key_exists($date, $this->__fields_data)) {
            $compare_date = strtotime($this->__field_data[$compare_date]);
            if ($compare_date === false) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
                return $this;
            }
        } else {
            $compare_date = strtotime($date);
            if ($compare_date === false) {
                throw new ValueError("VALIDATOR PARSE DATE: Không thể chuyển hóa ngày tháng '$date' thành ngày tháng. Vui lòng kiểm tra lại.");
            }
        }
        if (strtotime($this->get_current_field_data()) === false || $compare_date >= strtotime($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn là ngày ngay sau hoặc bằng ngày được chỉ định. Ngày tháng dạng chuỗi sẽ được truyền qua hàm `strtotime` để chuyển về dạng `DateTime`. 
     * @param string $date Ngày được chỉ định hoặc tên trường khác muốn được sử dụng để so sánh.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function after_or_equal($date, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_date = null;
        if (array_key_exists($date, $this->__fields_data)) {
            $compare_date = strtotime($this->__field_data[$compare_date]);
            if ($compare_date === false) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
                return $this;
            }
        } else {
            $compare_date = strtotime($date);
            if ($compare_date === false) {
                throw new ValueError("VALIDATOR PARSE DATE EXCEPTION: Không thể chuyển hóa ngày tháng '$date' thành ngày tháng. Vui lòng kiểm tra lại.");
            }
        }
        if (strtotime($this->get_current_field_data()) === false || $compare_date > strtotime($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ bao gồm các ký tự `[a-z][A-Z]` hoặc chữ số `[0-9]`.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function alpha_num($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!ctype_alnum($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải là một mảng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function array($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!is_array($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn là ngày ngay trước ngày được chỉ định. Ngày được chỉ định hoặc tên trường khác muốn được sử dụng để so sánh.
     * @param string $date Ngày được chỉ định hoặc tên trường khác muốn được sử dụng để so sánh.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function before($date, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_date = null;
        if (array_key_exists($date, $this->__fields_data)) {
            $compare_date = strtotime($this->__field_data[$compare_date]);
            if ($compare_date === false) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
                return $this;
            }
        } else {
            $compare_date = strtotime($date);
            if ($compare_date === false) {
                throw new ValueError("VALIDATOR PARSE DATE EXCEPTION: Không thể chuyển hóa ngày tháng '$date' thành ngày tháng. Vui lòng kiểm tra lại.");
            }
        }
        if (strtotime($this->get_current_field_data()) === false || $compare_date <= strtotime($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn là ngày ngay trước hoặc bằng ngày được chỉ định. Ngày tháng dạng chuỗi sẽ được truyền qua hàm `strtotime` để chuyển về dạng `DateTime`.
     * @param string $date Ngày được chỉ định hoặc tên trường khác muốn được sử dụng để so sánh.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function before_or_equal($date, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_date = null;
        if (array_key_exists($date, $this->__fields_data)) {
            $compare_date = strtotime($this->__field_data[$compare_date]);
            if ($compare_date === false) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
                return $this;
            }
        } else {
            $compare_date = strtotime($date);
            if ($compare_date === false) {
                throw new ValueError("VALIDATOR PARSE DATE EXCEPTION: Không thể chuyển hóa ngày tháng '$date' thành ngày tháng. Vui lòng kiểm tra lại.");
            }
        }
        if (strtotime($this->get_current_field_data()) === false || $compare_date < strtotime($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị nằm trong khoảng `($min, $max)`
     * @param int|float $min Giá trị nguyên hoặc thực nhỏ nhất của khoảng.
     * @param int|float $max Giá trị nguyên hoặc thực lớn nhất của khoảng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function between($min, $max, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!(is_int($min) || is_float($min))) {
            throw new InvalidArgumentException("VALIDATOR VALUE WRONG TYPE: Giá trị chỉ định value phải là số nguyên hoặc số thực. Phát hiện kiểu không hợp lệ '" . gettype($min) . "'.");
        }
        if (!(is_int($max) || is_float($max))) {
            throw new InvalidArgumentException("VALIDATOR VALUE WRONG TYPE: Giá trị chỉ định value phải là số nguyên hoặc số thực. Phát hiện kiểu không hợp lệ '" . gettype($max) . "'.");
        }

        if (filter_var($this->get_current_field_data(), FILTER_VALIDATE_INT) !== FALSE && filter_var($this->get_current_field_data(), FILTER_VALIDATE_FLOAT) !== FALSE && $this->get_current_field_data() > $min && $this->get_current_field_data() < $max) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải là ngày tháng. Ngày tháng không đi kèm theo thời gian. 
     * 
     * Chú ý, phương thức này tuân theo hàm `strtotime` mặc định của PHP để chuyển thành một đối tượng DateTime.. Để tùy chỉnh định dạng có thể sử dụng `date_format()`.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function date($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (strtotime($this->get_current_field_data()) === false) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải là ngày tháng bằng với ngày tháng được chỉ định. Phương thức này tuân theo hàm `strtotime` mặc định của PHP để chuyển thành một đối tượng DateTime.
     * @param string $date Ngày được chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function date_equal($date, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (strtotime($date) === false) {
            throw new InvalidArgumentException("VALIDATOR INVALID DATE: Tham số date phải là chuỗi dạng ngày tháng. Giá trị không hợp lệ '$date'");
        }
        if (strtotime($this->get_current_field_data()) !== strtotime($date)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải là ngày tháng có định dạng được chỉ định. Định dạng lại ngày tháng của dữ liệu request. Lưu ý, phương thức hỗ trợ kiểm tra cả thời gian.
     * @param string $format Định dạng của ngày được chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function date_format($format, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $datetime = DateTime::createFromFormat($format, $this->get_current_field_data());
        if ($datetime === false || ($datetime->format($format) !== $this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải là dạng số học có số lượng số sau dấu thập phân từ `$min` đến `$max`. Để quy định chính xác bao nhiêu số sau dấu thập phần, chỉ định `$min` = `$max`.
     * @param int $min số lượng số nhỏ nhất.
     * @param int $min số lượng số lớn nhất.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function decimal($min, $max, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $count = (int) strpos(strrev($this->get_current_field_data()), ".");
        if (!($min <= $count && $count <= $max)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn phải khác với trường được chỉ định.
     * @param string $field tên trường được chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function different($field_name, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if ($this->get_current_field_data() == $this->get_field_data($field_name)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn được bỏ qua trong quá trình xác thực. Khi sử dụng luật này, tất cả các luật nằm sau đều không có hiệu lực.
     */
    public function exclude()
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        unset($this->__fields_data[$this->__field_name]);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn được bỏ qua trong quá trình xác thực nếu như một trường thỏa mãn bằng một điều kiện nào đó. Cần được đặt ở đầu mỗi lượt xác thực.
     * @param string $field tên trường được chỉ định.
     * @param string $operator toán tử so sánh được chỉ định (`==`, `<=`, `>=`, `!=`, `<`, `>`). Mặc định là bằng `==`.
     * @param object $value giá trị được chỉ định.
     */
    public function exclude_if($field_name, $value, $operator = '==')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!array_key_exists($field_name, $this->__fields_data)) {
            return $this;
        }
        if (empty($this->__fields_data[$field_name])) {
            return $this;
        }
        switch ($operator) {
            case '==':
                if ($this->__fields_data[$field_name] == $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;
            case '<=':
                if ($this->__fields_data[$field_name] <= $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;
            case '>=':
                if ($this->__fields_data[$field_name] >= $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;
            case '!=':
                if ($this->__fields_data[$field_name] != $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;
            case '<':
                if ($this->__fields_data[$field_name] < $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;
            case '>':
                if ($this->__fields_data[$field_name] > $value) {
                    unset($this->__fields_data[$this->__field_name]);
                }
                break;

            default:
                throw new InvalidArgumentException("VALIDATOR INVALID OPERATOR: Toán tử không hợp lệ. Giá trị không hợp lệ '$operator'");
                break;
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn được bỏ qua trong quá trình xác thực nếu như hàm được truyền vào trả về giá trị `true`.
     * @param callable $func tên hàm được chỉ định. Có thể là closure.
     * @param array $args mảng các biến số được truyền vào.
     * @param object $value giá trị được chỉ định.
     */
    public function exclude_if_complex($func, $args = [])
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (call_user_func_array($func, $args)) {
            unset($this->__fields_data[$this->__field_name]);
        }
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị có định dạng trùng với mẫu $pattern. Mẫu $pattern là một biểu thức chính quy.
     * @param string $pattern biểu thức chính quy nhằm xác định định dạng của trường được chọn.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function like($pattern, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (preg_match($pattern, $this->get_current_field_data()) == false) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị không được nhỏ hơn giá trị chỉ định.
     * @param int|float $value Giá trị thực hoặc nguyên là giá trị nhỏ nhất mà trường được chọn có thể bằng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function min($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!(is_int($value) || is_float($value))) {
            throw new InvalidArgumentException("VALIDATOR VALUE WRONG TYPE: Giá trị chỉ định value phải là số nguyên hoặc số thực. Phát hiện kiểu không hợp lệ '" . gettype($value) . "'.");
        }
        if ($this->get_current_field_data() < $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị không được lớn hơn giá trị chỉ định.
     * @param int|float $value Giá trị thực hoặc nguyên là giá trị lớn nhất mà trường được chọn có thể bằng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function max($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!(is_int($value) || is_float($value))) {
            throw new InvalidArgumentException("VALIDATOR VALUE WRONG TYPE: Giá trị chỉ định value phải là số nguyên hoặc số thực. Phát hiện kiểu không hợp lệ '" . gettype($value) . "'.");
        }
        if ($this->get_current_field_data() > $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ bao gồm các ký tự unicode (Không bao gồm chữ số).
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function unicode($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $re = '/[\P{L}]+/u';
        $str = $this->get_current_field_data();

        if (preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn chỉ bao gồm các ký tự unicode và bao gồm chữ số.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function unicode_num($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $re = '/[^\p{L}0-9]+/u';
        $str = $this->get_current_field_data();
        if (preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị lớn hơn giá trị chỉ định. Đối với số là so sánh giá trị, với giá trị chuỗi sẽ so sánh số ký tự. Đối với mảng, so sánh số lượng phần tử của mảng. Đối với kiểu khác phải so sánh cùng kiểu.
     * @param mixed $value Giá trị chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function greater_than($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_value = $this->get_current_field_data();
        if (is_int($compare_value) || is_float($compare_value)) {
            $compare_value = floatval($compare_value);
            $value = floatval($value);
        } else if (is_string($compare_value)) {
            $compare_value = strlen($compare_value);
            $value = strlen($value);
        } else if (is_array($compare_value)) {
            $compare_value = count($compare_value);
            $value = count($value);
        }
        if ($compare_value <= $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị lớn hơn hoặc bằng giá trị chỉ định. Đối với số là so sánh giá trị, với giá trị chuỗi sẽ so sánh số ký tự. Đối với mảng, so sánh số lượng phần tử của mảng. Đối với kiểu khác phải so sánh cùng kiểu.
     * @param mixed $value Giá trị chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function greater_than_or_equal($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_value = $this->get_current_field_data();
        if (is_int($value) || is_float($value)) {
            $compare_value = floatval($compare_value);
            $value = floatval($value);
        } else if (is_string($value)) {
            $compare_value = strlen($compare_value);
            $value = strlen($value);
        } else if (is_array($value)) {
            $compare_value = count($compare_value);
            $value = count($value);
        }
        if ($compare_value < $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị nhỏ hơn giá trị chỉ định. Đối với số là so sánh giá trị, với giá trị chuỗi sẽ so sánh số ký tự. Đối với mảng, so sánh số lượng phần tử của mảng. Đối với kiểu khác phải so sánh cùng kiểu.
     * @param mixed $value Giá trị chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function less_than($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_value = $this->get_current_field_data();
        if (is_int($value) || is_float($value)) {
            $compare_value = floatval($compare_value);
            $value = floatval($value);
        } else if (is_string($value)) {
            $compare_value = strlen($compare_value);
            $value = strlen($value);
        } else if (is_array($value)) {
            $compare_value = count($compare_value);
            $value = count($value);
        }
        if ($compare_value >= $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị nhỏ hơn hoặc bằng giá trị chỉ định. Đối với số là so sánh giá trị, với giá trị chuỗi sẽ so sánh số ký tự. Đối với mảng, so sánh số lượng phần tử của mảng. Đối với kiểu khác phải so sánh cùng kiểu.
     * @param mixed $value Giá trị chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function less_than_or_equal($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $compare_value = $this->get_current_field_data();
        if (is_int($value) || is_float($value)) {
            $compare_value = floatval($compare_value);
            $value = floatval($value);
        } else if (is_string($value)) {
            $compare_value = strlen($compare_value);
            $value = strlen($value);
        } else if (is_array($value)) {
            $compare_value = count($compare_value);
            $value = count($value);
        }
        if ($compare_value > $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị phải thuộc giá trị trong mảng $array. Tức là ít nhất một lần xuất hiện trong mảng.
     * @param array $array Giá trị chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function in($array, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!in_array($this->get_current_field_data(), $array)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có kích thước bằng giá trị chỉ định. Đối với số là giá trị của số (Cần áp dụng thêm luật để xác định trường được chọn có kiểu nguyên). Với chuỗi sẽ là số ký tự. Đối với mảng, sẽ là số lượng phần tử của mảng. Đối với file (Là file tải lên hợp lệ), sẽ là kích thước của file (đơn vị byte). Chỉ áp dụng cho kiểu số nguyên, chuỗi, mảng và file. 
     * @param mixed $value Giá trị chỉ định. Phải là giá trị kiểu nguyên
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function size($value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!is_int($value)) {
            throw new InvalidArgumentException("VALIDATOR INVALID VALUE: Giá trị value không hợp lệ. Phải là kiểu nguyên. Giá trị nhận được '$value'");
        }
        $compare_value = $this->get_current_field_data();
        if (is_int($compare_value)) {
            $compare_value = floatval($compare_value);
        } else if (is_string($compare_value)) {
            $compare_value = strlen($compare_value);
        } else if (is_array($compare_value)) {
            $compare_value = count($compare_value);
        } else if ($compare_value instanceof FileUpload) {
            if ($compare_value->is_valid()) {
                $compare_value = $compare_value->get_file_size();
            }
        }
        if ($compare_value != $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    // KIỂM TRA KIỂU CỦA TRƯỜNG
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị nguyên. Khi được áp dụng luật này, trường dữ liệu được chọn được chuyển sang giá trị nguyên.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function integer($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $val = filter_var($this->get_current_field_data(), FILTER_VALIDATE_INT);
        if ($val !== FALSE) {
            $this->set_current_field_data($val);
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị thực. Khi được áp dụng luật này, trường dữ liệu được chọn được chuyển sang giá trị thực.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function float($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $val = filter_var($this->get_current_field_data(), FILTER_VALIDATE_FLOAT);
        if ($val !== FALSE) {
            $this->set_current_field_data($val);
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__, $message);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị kiểu boolean. Các giá trị được chấp nhận bao gồm: true, false, "true", "false", 1, 0, "1", "0". Khi được áp dụng luật này, trường dữ liệu được chọn được chuyển sang giá trị thực.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function boolean($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!in_array($this->get_current_field_data(), [
            true, false, "true", "false", 1, 0, "1", "0"
        ])) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        } else {
            $this->set_current_field_data(boolval("0"));
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có thể có giá trị `null`.
     */
    public function nullable()
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị kiểu chuỗi. Khi được áp dụng luật này, trường dữ liệu được chọn được chuyển sang giá trị chuỗi.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function string($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        if (!is_string($this->get_current_field_data())) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là một chuỗi URL hợp lệ.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function URL($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $val = filter_var($this->get_current_field_data(), FILTER_VALIDATE_URL);
        if ($val === FALSE) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    /**
     *  Trường được chọn sẽ được băm sử dụng thuật toán `$engine`. Thường sử dụng cho mật khẩu.
     * @param string $engine Thuật toán sử dụng để băm. Mặc định sẽ là `sha256`.
     */
    public function hashed($engine = 'sha256')
    {
        if (empty($engine)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY ENGINE: Tham số engine không được để trống!");
        }
        $val = hash($engine, $this->get_current_field_data());
        $this->set_current_field_data($val);
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn là một file được tải lên hợp lệ.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function file($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        } else if (!$file->is_valid()) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là file tải lên hợp lệ có kiểu file thỏa mãn các kiểu được chỉ định. Kiểu có dạng `<tên-kiểu>/<tên-kiểu-phụ>`, trong đó nếu `<tên-kiểu-phụ> = *` thì cho phép tất cả các kiểu phụ thuộc kiểu đó
     * @param array $type Danh sách các kiểu file sử dụng để chỉ định xác thực. Tham số không được để trống và phải là mảng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function has_file_type(array $types, $message = '')
    {
        if (empty($types)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY TYPE FILE: Danh sách kiểu file sử dụng để lọc không được để trống");
        }
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            return $this;
        }
        if (!$file->is_valid()) {
            return $this;
        }
        $file_type = $file->get_file_type();
        $file_sub_type = explode('/', $file_type)[1];
        $file_type = explode('/', $file_type)[0];
        $matched = false;
        foreach ($types as $type) {
            $type_array = explode('/', $type);
            if (empty($type_array)) {
                throw new InvalidArgumentException("VALIDATOR INVALID TYPE FILE: Kiểu '$type' không là kiểu hợp lệ!");
            }
            $type_main = $type_array[0];
            $type_sub = $type_array[1];
            if ($type_sub == '*') {
                if ($type_main == $file_type) {
                    $matched = true;
                    break;
                }
                continue;
            }
            if ($file_type == $type_main && $file_sub_type == $type_sub) {
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là file tải lên hợp lệ có file extension thuộc các file extensions được chỉ định.
     * @param array $extensions Danh sách các extension file sử dụng để chỉ định xác thực. Tham số không được để trống và phải là mảng.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function has_file_extension(array $extensions, $message = '')
    {
        if (empty($extensions)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY TYPE FILE: Danh sách kiểu file sử dụng để lọc không được để trống");
        }
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            return $this;
        }
        if (!$file->is_valid()) {
            return $this;
        }
        $file_extension = pathinfo($file->get_file_name(), PATHINFO_EXTENSION);
        $matched = false;
        foreach ($extensions as $extension) {
            if ($file_extension == $extension) {
                $matched = true;
                break;
            }
        }
        if (!$matched) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }
    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là file ảnh tải lên hợp lệ (có đuôi mở rộng là png, jpeg, jpg, bmp, gif, svg hoặc webp).
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function image($message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        $extensions = ['png', 'jpg', 'jpeg', 'bmp', 'gif', 'svg', 'webp'];
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            return $this;
        }
        if (!$file->is_valid()) {
            return $this;
        }
        $file_extension = pathinfo($file->get_file_name(), PATHINFO_EXTENSION);
        $matched = false;
        foreach ($extensions as $extension) {
            if (strtoupper($file_extension) == strtoupper($extension)) {
                $matched = true;
                break;
            }
        }
        if (!$matched) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là file tải lên hợp lệ và có kích thước không vượt quá giá trị được chỉ định.
     * @param int $value Kích thước tối đa cho phép của file. Đơn vị là byte.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function max_byte(int $value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            return $this;
        }
        if (!$file->is_valid()) {
            return $this;
        }

        $size = $file->get_file_size();
        if ($size > $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường dữ liệu được chọn có giá trị là file tải lên hợp lệ và có kích thước không thấp hơn giá trị được chỉ định.
     * @param int $value Kích thước tối đa cho phép của file. Đơn vị là byte.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function min_byte(int $value, $message = '')
    {
        if (!isset($this->__fields_data[$this->__field_name])) {
            return $this;
        }
        /**
         * @var FileUpload
         */
        $file = $this->get_current_field_data();
        if (!($file instanceof FileUpload)) {
            return $this;
        }
        if (!$file->is_valid()) {
            return $this;
        }

        $size = $file->get_file_size();
        if ($size < $value) {
            $this->set_error($this->__field_name, __FUNCTION__, $message);
        }
        return $this;
    }

    // VALIDATION WITH DATABASE
    /**
     * Trường được chọn phải có giá trị duy nhất trong bảng và trường được chỉ định. Nếu cột $field không được chỉ định thì tên trường được xét duy nhất sẽ chính là tên trường được chọn.
     * @param string $table_name Tên bảng được chỉ định.
     * @param string $field_name Tên trường được chỉ định.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function unique($table_name, $field_name = '', $message = '')
    {
        if (empty($table_name)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY TABLE NAME: Tham số table_name không được để trống!");
        }
        if (empty($field_name)) {
            $field_name = $this->__field_name;
        }

        try {
            $query = $this->__db->table($table_name)->where($field_name, "=", $this->get_current_field_data())->get();
            if (!is_null($query)) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
                $this->__previous_unique_table = $table_name;
                $this->__previous_unique_field = $field_name;
            }
            return $this;
        } catch (RuntimeException $ex) {
            throw new RuntimeException("VALIDATOR INVALID ARGUMENTS: Bảng '$table_name' không tồn tại trong CSDL hoặc trường '$field_name' không tồn tại trong bảng '$table_name'. Vui lòng kiểm tra lại. Exception này được ném từ exception sau: " . "'" . $ex->getMessage() . "'");
        }
    }
    /**
     * Trường được chọn sẽ được bỏ qua xác thực unique nếu như giá trị của trường có tên $field_name bằng giá trị $value được cho. Luật này chỉ đi kèm với luật `unique` và chỉ có tác dụng khi được gọi ngay sau luật `unique`.
     * @param string $field_name Tên trường được chỉ định.
     * @param mixed $value Giá trị chỉ định
     */
    public function ignore($field_name, $value)
    {
        if (empty($this->__previous_unique_table)) {
            return $this;
        }
        if (empty($field_name)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY FIELD NAME: Tham số field_name không được để trống!");
        }
        if (empty($value)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY FIELD NAME: Tham số value không được để trống!");
        }
        $table_name = $this->__previous_unique_table;
        try {
            $query = $this->__db->table($table_name)->where($this->__previous_unique_field, "=", $this->get_current_field_data())->where($field_name, "<>", $value)->get();
            if ($query == null) {
                if (array_key_exists("unique", $this->__errors[$this->__field_name])) {
                    unset($this->__errors[$this->__field_name]["unique"]);
                    if (count($this->__errors[$this->__field_name]) <= 0) {
                        unset($this->__errors[$this->__field_name]);
                    }
                }
            }
        } catch (RuntimeException $ex) {
            throw new RuntimeException("VALIDATOR INVALID ARGUMENTS: Trường '$field_name' không tồn tại trong bảng '$table_name'. Vui lòng kiểm tra lại. Exception này được ném từ exception sau: " . "'" . $ex->getMessage() . "'");
        } finally {
            $this->__previous_unique_table = "";
            $this->__previous_unique_field = "";
            return $this;
        }
    }
    /**
     * Trường được chọn sẽ chỉ xác thực unique nếu như truy vấn trong callable `$func` được thỏa mãn. Ví dụ dưới đây trình xác thực sẽ chỉ xác thực trường email duy nhất trên trường email bảng users chỉ trong phạm vi của bản ghi có user_id > 1 (Giả sử user_id = 1 là admin):
     * ```php
     * field('email')->unique('users')->where(fn (Database $query) => $query->where("user", ">", 1))
     * ```
     * @param callable $func Hàm closure phải có tham số đầu vào có biến kiểu `Database`
     * @example:
     * ```php
     * // Xác thực trường email có giá trị duy nhất khi và chỉ khi giá trị của trường user lớn hơn 1
     * field('email')
     * ->unique('users')
     * ->where(
     *      fn (Database $query) => $query->where("user", ">", 1)
     * );
     * ```
     */
    public function where($func)
    {
        if (empty($this->__previous_unique_table)) {
            return $this;
        }
        $table_name = $this->__previous_unique_table;
        $field_name = $this->__previous_unique_field;
        try {
            $query = $func($this->__db->table($table_name)->where($field_name, "=", $this->get_current_field_data()))->first();
            if (is_null($query)) {
                if (array_key_exists("unique", $this->__errors[$this->__field_name])) {
                    unset($this->__errors[$this->__field_name]["unique"]);
                    if (count($this->__errors[$this->__field_name]) <= 0) {
                        unset($this->__errors[$this->__field_name]);
                    }
                }
            }
        } catch (RuntimeException $ex) {
            throw new RuntimeException("VALIDATOR INVALID ARGUMENTS: Có lỗi trong quá trình truy vấn. Vui lòng kiểm tra lại. Exception này được ném từ exception sau: " . "'" . $ex->getMessage() . "'");
        } finally {
            $this->__previous_unique_table = "";
            $this->__previous_unique_field = "";
            return $this;
        }
    }
    /**
     * Trường được chọn phải có giá trị tồn tại trong trường thuộc bảng được chỉ định trong CSDL. Nếu không chỉ định tên trường thì tên trường được chỉ định sẽ là trường được chọn.
     * @param string $table_name Tên bảng được chỉ định
     * @param string $field_name Tên trường được chỉ định. Mặc định là rỗng. Nếu là rỗng, trường được chỉ định sẽ là trường được chọn để xác thực
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function exists($table_name, $field_name = '', $message = '')
    {
        if (empty($table_name)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY TABLE NAME: Tham số table_name không được để trống!");
        }
        if (empty($field_name)) {
            $field_name = $this->__field_name;
        }

        try {
            $query = $this->__db->table($table_name)->where($field_name, "=", $this->get_current_field_data())->first();
            if (is_null($query)) {
                $this->set_error($this->__field_name, __FUNCTION__, $message);
            }
        } catch (RuntimeException $ex) {
            throw new RuntimeException("VALIDATOR INVALID ARGUMENTS: Bảng '$table_name' không tồn tại trong CSDL hoặc trường '$field_name' không tồn tại trong bảng '$table_name'. Vui lòng kiểm tra lại. Exception này được ném từ exception sau: " . "'" . $ex->getMessage() . "'");
        } finally {
            return $this;
        }
    }
}
