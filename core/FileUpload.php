<?php

namespace app\core\utils;

use Exception;
use InvalidArgumentException;

use function app\core\helper\public_path;

class FileUpload
{
    private $__file;

    public function __construct(string $file_field_name)
    {
        if (empty($file_field_name)) {
            throw new InvalidArgumentException("FILE UPLOAD EMPTY FIELD: Tên trường của file không được để trống!");
        }
        if (!array_key_exists($file_field_name, $_FILES)) {
            $this->__file = [];
        } else $this->__file = $_FILES[$file_field_name];
    }

    /**
     * Lưu file upload trên thư mục `public/files`, hoặc được chỉ định bởi người dùng. Chú ý tên thư mục không đi kèm tên file, do tên file được sinh tự động từ hệ thống. Để có thể lưu file dưới tên khác, xem thêm `store_as()`.
     * @param string $dir đường dẫn thư mục chứa file. Thư mục cha luôn là public. Mặc định là `files`, ám chỉ `public/file`. 
     */
    public function store(string $dir = 'files')
    {
        if (!is_dir(public_path($dir))) {
            throw new Exception("FILE UPLOAD DIR NOT FOUND: Không tìm thấy đường dẫn lưu file '" . public_path($dir) . "' trong thư mục 'public'");
        }
        if (!$this->is_valid()) {
            return false;
        }
        $file_type = pathinfo($this->get_file_name())['extension'];
        $destination = public_path($dir . "/" . md5($this->get_file_tmp_name()) . ".$file_type");
        if (!move_uploaded_file($this->get_file_tmp_name(), $destination)) {
            return false;
        }
        return $destination;
    }
    /**
     * Lưu file upload trên thư mục `public/files`, hoặc được chỉ định bởi người dùng, với tên khác.
     * @param string $dir đường dẫn thư mục chứa file. Thư mục cha luôn là public. Mặc định là `files`, ám chỉ `public/file`.
     * @param string $file_name tên file do người dùng chỉ định 
     */
    public function store_as(string $dir = 'files', string $file_name = '')
    {
        if (!is_dir(public_path($dir))) {
            throw new Exception("FILE UPLOAD DIR NOT FOUND: Không tìm thấy đường dẫn lưu file '" . public_path($dir) . "' trong thư mục 'public'");
        }
        if (!$this->is_valid()) {
            return false;
        }
        if (empty($file_name)) {
            $file_name = $this->get_file_name();
        }

        $destination = public_path($dir . "/" . $file_name);

        if (!move_uploaded_file($this->get_file_tmp_name(), $destination)) {
            return false;
        }
        return $file_name;
    }

    /**
     * Kiểm tra xem file upload có tồn tại hợp lệ hay không.
     * @return bool Trả về `true` nếu file upload thành công và hợp lệ. Ngược lại trả về `false`.
     */
    public function is_valid(): bool
    {
        if (empty($this->__file)) {
            return false;
        }
        if ($this->__file['error'] != UPLOAD_ERR_OK) {
            return false;
        }
        return true;
    }

    /**
     * Trả về mã lỗi của file upload.
     * @return int Mã lỗi của file upload.
     */
    public function get_error_code(): int
    {
        if (empty($this->__file)) {
            return -1;
        }
        return $this->__file['error'];
    }

    /**
     * Trả về kích thước file upload (Đơn vị bytes).
     */
    public function get_file_size(): int|false
    {
        return $this->get('size');
    }

    /**
     * Trả về kiểu file của file upload.
     */
    public function get_file_type()
    {
        return $this->get('type');
    }

    /**
     * Trả về tên file upload trên máy client.
     */
    public function get_file_name()
    {
        return $this->get('name');
    }

    /**
     * Trả về tên file upload tạm thời trên server
     */
    public function get_file_tmp_name()
    {
        return $this->get('tmp_name');
    }

    /**
     * Trả về thông báo lỗi dựa theo giá trị mã lỗi.
     * @param int $value Giá trị mã lỗi.
     */
    public static function get_error_message(int $value)
    {
        switch ($value) {
            case -1:
                return "Không thấy file. Kiểm tra trường file có đúng chưa hoặc kiểm tra cấu hình file_upload trong file 'php.ini'";
                break;
            case UPLOAD_ERR_OK:
                return "Tải file thành công";
                break;
            case UPLOAD_ERR_INI_SIZE:
                return "Kích thước file lớn hơn kích thước chỉ định trong file 'php.ini'";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                return "Kích thước file lớn hơn kích thước chỉ định trong form html";
                break;
            case UPLOAD_ERR_PARTIAL:
                return "Tải file chưa hoàn tất";
                break;
            case UPLOAD_ERR_NO_FILE:
                return "Không có file được tải";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Không tìm thấy folder tạm chứa file upload";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                return "Ghi file ra ổ đĩa thất bại";
                break;
            case UPLOAD_ERR_EXTENSION:
                return "Một extention PHP đã chặn việc tải file";
                break;

            default:
                return "Lỗi không xác định";
                break;
        }
    }

    private function get($type)
    {
        if (empty($this->__file)) {
            return false;
        }
        return $this->__file[$type];
    }
}
