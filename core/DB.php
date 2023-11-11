<?php

namespace app\core\db;

class DB
{
    private $__db;
    function __construct()
    {
        $this->__db = new Database();
    }
    public function get_db()
    {
        return $this->__db;
    }
}
