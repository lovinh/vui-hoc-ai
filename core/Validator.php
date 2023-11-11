<?php


namespace app\core\http_context;

use app\core\db\Database;

use InvalidArgumentException;
use ValueError;
use RuntimeException;
use BadMethodCallException;

class Validator
{
    private $__field_name = "";
    private $__fields_data = [];
    private $__errors = [];
    private $__messages = [];
    private $__have_error = false;
    private $__db = null;

    use DefinedRules;

    public function __construct()
    {
        $this->__db = new Database();
    }
    private function get_current_field_data()
    {
        if (!empty($this->__field_name)) {
            return $this->__fields_data[$this->__field_name];
        }
        return null;
    }

    /**
     * Thiết lập dữ liệu cho quá trình xác thực. Việc này là bắt buộc trước khi thực hiện việc xác thực.
     * @param array $field_data dữ liệu thu thập được cho quá trình xác thực.
     */
    public function set_fields_data($fields_data = [])
    {
        $this->__fields_data = $fields_data;
    }
    /**
     * 
     */
    public function set_current_field_data($value)
    {
        if (!empty($this->__field_name)) {
            $this->__fields_data[$this->__field_name] = $value;
        }
    }
    /**
     * Trả về dữ liệu lưu trữ trong quá trình xác thực. Nếu gọi trước việc xác thực có thể gây mất một số ảnh hưởng của việc xác thực lên dữ liệu
     * @return array Mảng chứa dữ liệu đã thiết lập.
     */
    public function get_fields_data()
    {
        return $this->__fields_data;
    }
    /**
     * Trả về dữ liệu lưu trữ của một trường cụ thể.
     * @param string $field_name Tên trường cần lấy dữ liệu
     * @return array Mảng chứa dữ liệu đã thiết lập.
     */
    public function get_field_data($field_name)
    {
        if (empty($field_name)) {
            throw new InvalidArgumentException("VALIDATOR EMPTY FIELD_NAME: Tên trường không được để trống");
        }
        if (empty($this->__fields_data[$field_name]))
            return null;
        return $this->__fields_data[$field_name];
    }
    /**
     * Chỉ định tên trường được chọn để thực hiện xác thực
     * @param string $field_name Tên trường được chọn. 
     */
    public function field($field_name)
    {
        if (empty($this->__fields_data)) {
            throw new ValueError("VALIDATOR ERROR: Chưa thiết lập dữ liệu cho quá trình xác thực! Có lẽ bạn quên chỉ định validator->set_fields_data()");
        }
        if (!array_key_exists($field_name, $this->__fields_data)) {
            throw new ValueError("VALIDATOR ERROR: Trường '$field_name' không tồn tại trong mảng field_data! Kiểm tra lại các trường có trong request!");
        }
        if (!empty(trim($field_name))) {
            $this->__field_name = $field_name;
        }
        return $this;
    }
    /**
     * Thiết lập các thông báo cho từng loại xác thực cụ thể.
     * @param array $messages Mảng có dạng `["tên-trường.tên-xác-thực" => "thông-báo"]`.
     */
    public function set_message($messages = [])
    {
        $this->__messages = $messages;
        return $this;
    }
    /**
     * Lấy ra lỗi đầu tiên của một trường cụ thể. Nếu không chỉ định trường, một mảng lỗi gồm các lỗi đầu tiên của từng trường sẽ được trả về
     * @param string $field_name Tên trường được chỉ định
     * @return array Trả về một mảng chứa lỗi. Nếu không chỉ định trường, một mảng lỗi gồm các lỗi đầu tiên của từng trường sẽ được trả về. Nếu không có lỗi, mảng rỗng được trả về.
     */
    public function get_first_error($field_name = "")
    {
        if (empty($this->__errors)) {
            return [];
        }
        if (empty($field_name)) {
            $errors_array = [];
            foreach ($this->__errors as $key => $value) {
                $errors_array[$key] = reset($value);
            }
            return $errors_array;
        }
        if (!array_key_exists($field_name, $this->__errors)) {
            throw new ValueError("VALIDATOR ERROR: Trường '$field_name' không tồn tại trong mảng errors! Kiểm tra lại các trường có trong request!");
        }
        return reset($this->__errors[$field_name]);
    }
    /**
     * Lấy ra mảng chứa tập các lỗi của tất cả các trường hoặc một trường cụ thể
     * @param string $field_name Tên trường được chỉ định. Mặc định là rỗng. Nếu là rống, toàn bộ lỗi của quá trình xác thực được trả về.
     * @return array Trả về một mảng chứa lỗi. Nếu không có lỗi, mảng rỗng được trả về.
     */
    public function get_errors($field_name = "")
    {
        if (empty($field_name)) {
            return $this->__errors;
        }
        if (!empty($this->__errors)) {
            return $this->__errors[$field_name];
        }
    }
    private function set_error($field_name, $rule_name, $message)
    {
        if ((!empty($message)) || (!array_key_exists($this->__field_name . '.' . $rule_name, $this->__messages)) || empty($this->__messages[$this->__field_name . '.' . $rule_name])) {
            $this->__messages[$this->__field_name . '.' . $rule_name] = trim($message);
        }
        $this->__errors[$field_name][$rule_name] = $this->__messages[$field_name . '.' . $rule_name];
        $this->__have_error = true;
    }
    /**
     * Kiểm tra xem xác thực có xảy ra lỗi hay không
     * @return bool Trả về `true` nếu có lỗi xác thực. Ngược lại trả về `false`.
     */
    public function is_error()
    {
        return $this->__have_error;
    }

    /**
     * Phương thức xác thực yêu cầu trường được chọn phải thỏa mãn hàm do người dùng định nghĩa. 
     * @param string $rule_name Tên của luật tùy chọn
     * @param callable $func Hàm callback do người dùng định nghĩa. Phải có ít nhất một biến số và trả về kiểu `bool`.
     * @param array $args Danh sách tham số truyền thêm vào hàm `$func`. Mặc định hàm `$func` được truyền vào dữ liệu tại trường đang xét, danh sách tham số sẽ bổ sung thêm cho hàm.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function callback($rule_name, $func, $args = [], $message = "")
    {
        array_unshift($args, $this->get_current_field_data());
        if (call_user_func_array($func, $args)) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__ . '_' . $rule_name, $message);
        return $this;
    }

    /**
     * Phương thức xác thực yêu cầu trường được chọn phải thỏa mãn luật do người dùng định nghĩa. Luật này là một lớp nằm trong thư mục `app/rules` (Nếu chưa có phải khởi tạo) và triển khai interface `Rule`.
     * @param string $rule_name Tên của luật tùy chọn. Đây phải là một lớp được định nghĩa trong `app/rules`
     * @param array $args Danh sách tham số truyền thêm vào. Mặc định là trường đang xét, danh sách tham số sẽ bổ sung thêm cho đối tượng luật.
     * @param string $message Thông báo nếu vi phạm trường xác thực này. Mặc định để trống. Nếu đã cài đặt thông báo tại phương thức `set_message()` thì thông báo đó sẽ bị ghi đè bởi thông báo mới.
     */
    public function custom($rule_name, $args = [], $message = '')
    {
        global $validate_config;
        $rule_name .= "app/core/http_context";
        if (!$validate_config["apply_custom_rule"]) {
            throw new RuntimeException("VALIDATOR ACCESS DENIED: Không thể sử dụng luật tùy chỉnh khi chưa chỉ định cấu hình validate_config['apply_custom_rule']. Vui lòng kiểm tra lại");
        }
        if (!class_exists($rule_name)) {
            throw new InvalidArgumentException("VALIDATOR UNKNOWN RULE: Luật '$rule_name' không tồn tại. Vui lòng kiểm tra lại");
        }
        if (!method_exists($rule_name, 'validate')) {
            throw new BadMethodCallException("VALIDATOR BAD METHOD: Lớp được chỉ định không tồn tại phương thức 'validate()'. Vui lòng kiểm tra lại");
        }
        $args["field"] = $this->get_current_field_data();
        $rule = new $rule_name();
        if ($rule->validate($args)) {
            return $this;
        }
        $this->set_error($this->__field_name, __FUNCTION__ . '_' . $rule_name, message: $message);
        return $this;
    }
}
