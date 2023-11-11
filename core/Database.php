<?php

namespace app\core\db;

use RuntimeException;
use mysqli_result;

/**
 * Lớp hỗ trợ kết nối và truy vấn đến cơ sở dữ liệu
 */
class Database
{
    private $__conn;

    use QueryBuilder;

    function __construct()
    {
        $this->__conn = Connection::getInstance();
    }
    /**
     * Sử dụng để thực hiện truy vấn một câu lệnh SQL tùy chỉnh trên cơ sở dữ liệu.
     * @param string sql Câu lệnh sql sử dụng để truy vấn
     * @return mysqli_result|bool Trả về một đối tượng mysqli_result cho câu lệnh SELECT và trả về kết quả bool cho câu lệnh INSERT, UPDATE, DELETE.
     */
    public function query($sql)
    {
        $result = $this->__conn->get_connection()->query($sql);
        if (!empty($this->__conn->get_connection()->connect_error)) {
            throw new RuntimeException("DATABASE CONNECTION FAIL: " . $this->__conn->get_connection()->connect_error . "! ('sql='" . $sql . "')");
        }
        if (!empty($this->__conn->get_connection()->error)) {
            throw new RuntimeException("DATABASE QUERY FAIL: " . $this->__conn->get_connection()->error . "! ('sql='" . $sql . "')");
        }
        return $result;
    }

    /**
     * Truy vấn SELECT cho bảng được chỉ định
     * @param string $table Bảng chọn để truy vấn SELECT
     * @param string $field Danh sách cột (trường) được chọn. Nếu có nhiều cột (trường), các cột được ghi cách nhau bởi dấu phẩy. Ví dụ: `"cột_1, cột_2, cột_3,..."`. Nếu không chỉ định trường, mặc định tất cả các cột (trường) được chọn
     * @param array|null $optional Mảng các tùy chỉnh sẽ có. Các tùy chính có thể sử dụng có cấu trúc: ["tên_tùy_chỉnh" => giá_trị], trong đó tên tùy chỉnh là một chuỗi và giá trị là các giá trị chuỗi, boolean hoặc số. Các tùy chỉnh có thể sử dụng bao gồm: `"distinct", "condition", "order_by", "limit", "offset", "desc"`
     * @return array|null Mảng chứa kết quả trả về nếu có dữ liệu truy vấn thành công. Ngược lại, nếu không có dữ liệu truy vấn trả về `null` 
     */
    public function select(string $table, $field = "*", $optional = [])
    {
        $condition = "";
        $distinct = "";
        $order_by = "";
        $limit = "";
        $offset = "";
        $desc = "";
        if (isset($optional['distinct'])) {
            $distinct = "DISTINCT";
        }
        if (isset($optional["condition"])) {
            $condition = "WHERE " . $optional["condition"];
        }
        if (isset($optional["order_by"])) {
            $order_by = "ORDER BY " . implode(",", $optional["order_by"]);
        }
        if (isset($optional["limit"])) {
            $limit = "LIMIT " . $optional["limit"];
            if (isset($optional["offset"])) {
                $offset = "OFFSET " . $optional["offset"];
            }
        }
        if (isset($optional["desc"])) {
            $desc = "DESC" . $optional["desc"];
        }
        $sql = "SELECT " . $distinct . " " . $field . " FROM " . $table . " " . $condition . " " . $order_by . " " . $desc . " " . $limit . " " . $offset . ";";

        $result = $this->query($sql);

        if (mysqli_num_rows($result) < 1) {
            return null;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Truy vấn chèn dữ liệu vào một bảng cụ thể
     * @param string table Tên bảng cần chèn. Chỉ cho phép chèn một bảng.
     * @param array field_values Mảng dữ liệu cần chèn. Mảng có cấu trúc dạng [`field` => `data`].
     * @return bool Trả về true nếu chèn thành công. Trả về false nếu ngược lại.
     */
    public function insert($table, $fields_values = [])
    {
        $fields = implode(",", array_keys($fields_values));
        $values = implode("','", array_values($fields_values));
        $sql = "INSERT INTO $table($fields) VALUES ('$values')";
        return $this->query($sql);
    }

    /**
     * Truy vấn UPDATE cho bảng được chỉ định với giá trị và điều kiện cho trước.
     * @param string $table Tên bảng được chỉ định
     * @param array $field_values Mảng chứa tên cột (trường) và giá trị cập nhật, có dạng `["tên_trường"=> giá_trị]`.
     * @param string $condition Điều kiện của truy vấn. Nếu không chỉ định điều kiện, toàn bộ các bản ghi trong bảng sẽ được cập nhật
     * @return bool Trả về `true` nếu cập nhật thành công. Ngược lại trả về `false`.
     */
    public function update($table, $fields_values = [], $condition = "1")
    {
        $setting = "";
        foreach ($fields_values as $key => $value) {
            $setting .= "$key = '" . $value . "',";
        }
        $setting = rtrim($setting, ',');
        $sql = "UPDATE $table SET $setting WHERE $condition;";
        return $this->query($sql);
    }

    /**
     * Truy vấn DELETE cho bảng được chỉ định với điều kiện cho trước.
     * @param string $table Tên bảng được chỉ định
     * @param string $condition Điều kiện của truy vấn. Nếu không chỉ định điều kiện, việc xóa không được thực hiện
     * @return bool Trả về `true` nếu xóa thành công. Ngược lại trả về `false`.
     */
    public function delete($table, $condition = "0")
    {
        $sql = "DELETE FROM $table WHERE " . $condition;
        return $this->query($sql);
    }
}
