<?php

namespace app\core\helper;

use app\core\model\BaseModel;
use app\core\utils\LoadUtils;

if (!function_exists("load_model")) {
    /**
     * Hàm sử dụng để load một model. Hàm này là alias của hàm LoadUtils::load_model
     */
    function load_model($model_name)
    {
        return LoadUtils::load_model($model_name);
    }
}
if (!function_exists("load_view")) {
    /**
     * Hàm sử dụng để load một model. Hàm này là alias của hàm LoadUtils::load_model
     */
    function load_view($view_name, $data = [])
    {
        return LoadUtils::load_view($view_name, $data);
    }
}

if (!function_exists("render_body")) {
    function render_block($view_name, $data = [])
    {
        return LoadUtils::render_block($view_name, $data);
    }
}
