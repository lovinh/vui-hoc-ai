<?php

namespace app\core\db;

trait QueryBuilder
{
    private $_table;
    private $_where;
    private $_select_field = '*';
    private $_order_by_field;
    private $_limit;
    private $_join;
    /**
     * Lựa chọn bảng cần truy vấn. Cần thiết trong các truy vấn. Chỉ cho phép chọn duy nhất 1 bảng
     * @param string $table_name tên bảng cần chọn.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function table($table_name)
    {
        $this->_table = $table_name;
        return $this;
    }
    /**
     * Lựa chọn cột(trường) cần truy vấn.
     * @param string $field tên cột (trường) cần chọn. Để chọn nhiều cột, viết các tên cột (trường) trong cùng một chuỗi, ngăn cách nhau bởi dấu `,`. Ví dụ: `"cột_1, cột_2, ... , cột_n"`. Nếu không chỉ định tên cột, mặc địch `$field="*".
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function select_field($field = "*")
    {
        $this->_select_field = $field;
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. Nếu gọi nhiều lần hàm `where()`, điều kiện được nối thêm bởi toán tử `AND`.
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $compare toán tử sử dụng để làm điều kiện. Ví dụ: `"="`, `"!="`, `"<"`, `">"`, ...
     * @param string $value giá trị so sánh.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function where($field, $compare, $value)
    {
        if (!empty($this->_where)) {
            $this->_where .= " AND $field $compare '$value'";
        } else {
            $this->_where = " WHERE $field $compare '$value'";
        }
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. Sử dụng cho điều kiện hoặc. Khi gọi `orwhere()` điều kiện sẽ được nối thêm `OR`. Nếu chỉ có một lệnh `orwhere()` duy nhất trong truy vấn thì điều kiện tương tự `where()`
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $compare toán tử sử dụng để làm điều kiện. Ví dụ: `"="`, `"!="`, `"<"`, `">"`, ...
     * @param string $value giá trị so sánh.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function orwhere($field, $compare, $value)
    {
        if (!empty($this->_where)) {
            $this->_where .= " OR $field $compare '$value'";
        } else {
            $this->_where = " WHERE $field $compare '$value'";
        }
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn có cấu trúc giống như nào. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. 
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $compare toán tử sử dụng để làm điều kiện. Ví dụ: `"="`, `"!="`, `"<"`, `">"`, ...
     * @param string $value giá trị so sánh.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function where_like($field, $pattern)
    {
        if (!empty($this->_where)) {
            $this->_where .= " AND $field LIKE '$pattern'";
        } else {
            $this->_where = " WHERE $field LIKE '$pattern'";
        }
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn có cấu trúc giống như nào. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. Tuy nhiên sẽ là điều kiện hoặc. Nếu không có điều kiện trước thì phương thức hoạt động như `where_like()`.
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $compare toán tử sử dụng để làm điều kiện. Ví dụ: `"="`, `"!="`, `"<"`, `">"`, ...
     * @param string $value giá trị so sánh.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function or_where_like($field, $pattern)
    {
        if (!empty($this->_where)) {
            $this->_where .= " OR $field LIKE '$pattern'";
        } else {
            $this->_where = " WHERE $field LIKE '$pattern'";
        }
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn có nằm giữa khoảng nào. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. 
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $first giá trị bắt đầu. Có thể là số nguyên, chữ hoặc ngày tháng (`Y-m-d`).
     * @param string $second giá trị kết thúc. Có thể là số nguyên, chữ hoặc ngày tháng (`Y-m-d`).
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function where_between($field, $first, $second)
    {
        if (!empty($this->_where)) {
            $this->_where .= " AND $field BETWEEN '$first' AND '$second'";
        } else {
            $this->_where = " WHERE $field BETWEEN '$first' AND '$second'";
        }
        return $this;
    }
    /**
     * Chỉ định điều kiện của truy vấn có nằm giữa khoảng nào. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. Tuy nhiên, điều kiện sẽ được là phép hoặc giữa các điều kiện trước đó và điều kiện mới. Nếu không có điều kiện trước đó phương thức hoạt động tương tự `where_between()`.
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param string $first giá trị bắt đầu. Có thể là số nguyên, chữ hoặc ngày tháng (`Y-m-d`).
     * @param string $second giá trị kết thúc. Có thể là số nguyên, chữ hoặc ngày tháng (`Y-m-d`).
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function or_where_between($field, $first, $second)
    {
        if (!empty($this->_where)) {
            $this->_where .= " OR $field BETWEEN '$first' AND '$second'";
        } else {
            $this->_where = " WHERE $field BETWEEN '$first' AND '$second'";
        }
        return $this;
    }

    /**
     * Chỉ định điều kiện của truy vấn có thuộc danh sách giá trị được cho hay không. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. 
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param array $list danh sách giá trị sử dụng trong điều kiện.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function where_in($field, $list)
    {
        $str_list = "('" . implode("', '", $list) . "')";
        if (!empty($this->_where)) {
            $this->_where .= " AND $field IN " . $str_list;
        } else {
            $this->_where = " WHERE $field IN " . $str_list;
        }
        return $this;
    }

    /**
     * Chỉ định điều kiện của truy vấn có thuộc danh sách giá trị được cho hay không. Sử dụng trong các truy vấn SELECT, UPDATE, DELETE. Tuy nhiên, điều kiện sẽ được là phép hoặc giữa các điều kiện trước đó và điều kiện mới. Nếu không có điều kiện trước đó phương thức hoạt động tương tự `where_in()`. 
     * @param string $field tên cột (trường) cần chọn. Chỉ được chọn một cột.
     * @param array $list danh sách giá trị sử dụng trong điều kiện.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function or_where_in($field, $list)
    {
        $str_list = "('" . implode("', '", $list) . "')";
        if (!empty($this->_where)) {
            $this->_where .= " OR $field IN " . $str_list;
        } else {
            $this->_where = " WHERE $field IN " . $str_list;
        }
        return $this;
    }

    /**
     * Chỉ định kết quả truy vấn trả về theo thứ tự sắp xếp của các cột (trường nào). Chỉ sử dụng với truy vấn SELECT.
     * @param string $field Tên cột (trường) sử dụng để sắp xếp. Nếu có nhiều cột (trường), các cột được ghi cách nhau bởi dấu phẩy. Ví dụ: `"cột_1, cột_2, cột_3"`
     * @param bool $desc Chỉ định sắp xếp tăng dần hay giảm dần. Chỉ có tác dụng nếu chọn 1 cột.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function order_by($field, $desc = false)
    {
        $field_array = array_filter(explode(',', $field));
        if (!empty($field_array) && count($field_array) >= 2) {
            $this->_order_by_field = "ORDER BY " . implode(', ', $field_array);
        } else {
            $this->_order_by_field = "ORDER BY " . $field . " " . ($desc ? "DESC" : "ASC");
        }
        return $this;
    }
    /**
     * Chỉ định giới hạn số lượng bản ghi trả về từ truy vấn SELECT
     * @param int $number Số lượng bản ghi được trả về từ truy vấn
     * @param int $offset Số bản ghi sẽ được bỏ qua trong quá trình trả về. Ví dụ, nếu chỉ định limit có number = 5 thì 5 bản ghi, bắt đầu từ bản ghi số 0 được trả về, nhưng nếu offset = 3, thì 5 bản ghi bắt đầu từ 2 được trả về.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function limit($number, $offset = 0)
    {
        $this->_limit = " LIMIT $number OFFSET $offset";
        return $this;
    }
    // Inner join
    /**
     * Chỉ định phép nối của bảng đang được chọn với bảng được cho bởi tham số. Bảng đang được chọn được chỉ định bởi phương thức `table()`. Phép nối ở đây là phép nối trong (inner join).
     * @param string $table_name Tên bảng được nối với bảng đang chọn
     * @param string $condition Điều kiện nối bảng.
     * @return Database trả về chính đối tượng Database được sử dụng. Cho phép các truy vấn tiếp theo được thực hiện.
     */
    public function join($table_name, $condition)
    {
        $this->_join .= "INNER JOIN " . $table_name . " ON " . $condition . " ";
        return $this;
    }
    /**
     * chèn dữ liệu vào bảng được chọn bởi `table()` theo dữ liệu truyền bởi biến `$data`.
     * @param array $data mảng lưu trữ dữ liệu cần chèn theo dạng `["tên_field" => "dữ_liệu_mới"]`. Chỉ chèn được 1 bản ghi một lần.
     * @return bool trả về `true` nếu chèn thành công, ngược lại trả về `false`.
     */
    public function insert_value($data = [])
    {
        $table_name = $this->_table;
        $this->reset();
        return $this->insert($table_name, $data);
    }
    /**
     * Cập nhật dữ liệu thỏa mãn truy vấn trong `where()` trên bảng được chọn bởi `table()` theo dữ liệu truyền bởi biến `$data`. Nếu không sử dụng `where()`, thực hiện cập nhật toàn bộ các dữ liệu của các bản ghi trong bảng.
     * @param array $data mảng lưu trữ dữ liệu cập nhật theo dạng `["tên_field" => "dữ_liệu_mới"]`.
     * @return bool trả về `true` nếu cập nhật thành công, ngược lại trả về false.
     */
    public function update_value($data = [])
    {
        $where_clause = trim(str_replace("WHERE", '', $this->_where));
        $table_name = $this->_table;
        $this->reset();
        return $this->update($table_name, $data, $where_clause);
    }
    /**
     * Xóa dữ liệu thỏa mãn truy vấn trong `where()` trên bảng được chọn bởi `table()`. Nếu không sử dụng `where()`, thực hiện xóa toàn bộ các dữ liệu tồn tại trong bảng.
     * @return bool trả về `true` nếu xóa thành công, ngược lại trả về false.
     */
    public function delete_value()
    {
        $where_clause = trim(str_replace("WHERE", '', $this->_where));
        $table_name = $this->_table;
        $this->reset();
        return $this->delete($table_name, $where_clause);
    }
    /**
     * Lấy ra toàn bộ dữ liệu được chọn theo dạng mảng liên kết
     * @return array|null Trả về mảng kết quả nếu có dữ liệu thỏa mãn truy vấn, ngược lại trả về null.
     */
    public function get()
    {
        $sql = "SELECT $this->_select_field FROM $this->_table $this->_join $this->_where $this->_order_by_field $this->_limit";

        $result = $this->query($sql);
        $this->reset();
        if (mysqli_num_rows($result) < 1) {
            return null;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Lấy ra dữ liệu đầu tiên trong các kết quả được chọn theo dạng mảng liên kết
     * @return array|null Trả về 1 kết quả nếu có dữ liệu thỏa mãn truy vấn, ngược lại trả về null.
     */
    public function first()
    {
        $sql = "SELECT $this->_select_field FROM $this->_table $this->_join $this->_where $this->_order_by_field $this->_limit";

        $result = $this->query($sql);
        $this->reset();
        if (mysqli_num_rows($result) < 1) {
            return null;
        }
        return $result->fetch_assoc();
    }
    /**
     * Lấy ra câu lệnh truy vấn xây dựng từ các truy vấn con đã tạo. Chỉ được sử dụng ở cuối chuỗi truy vấn.
     * @return string Câu lệnh truy vấn.
     */
    public function get_sql()
    {
        $sql = "SELECT $this->_select_field FROM $this->_table $this->_join $this->_where $this->_order_by_field $this->_limit";
        return $sql;
    }
    private function reset()
    {
        $this->_table = "";
        $this->_where = "";
        $this->_select_field = "*";
        $this->_limit = "";
        $this->_order_by_field = "";
        $this->_join = "";
    }
}
