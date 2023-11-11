<?php

namespace app\core\db;

use mysqli;

/**
 * Lớp thực hiện tạo kết nối với cơ sở dữ liệu. Được viết theo mẫu thiết kế Singleton
 */
class Connection
{
    private static $instance = null;

    private $__conn;

    private function __construct()
    {
        // Connect to the database;
        global $database_config;
        mysqli_report(MYSQLI_REPORT_STRICT);
        $this->__conn = new mysqli($database_config["server"], $database_config["user"], $database_config["password"], $database_config["db_name"]);
    }

    /**
     * Trả về đối tượng lớp Connection. Nếu chưa được khởi tạo, một đối tượng Connection mới sẽ được tạo và trả về. Nếu đã có đối tượng Connection được khởi tạo, trả về bản thân đối tượng đó.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Connection();
        }

        return self::$instance;
    }

    /**
     * Trả về kết nối database hiện có
     */
    public function get_connection()
    {
        return $this->__conn;
    }
}
