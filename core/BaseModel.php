<?php

namespace app\core\model;

use app\core\db\Database;

/**
 * Lớp nền cho các lớp model người dùng định nghĩa.
 */
class BaseModel
{
    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }
}
