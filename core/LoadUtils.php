<?php

namespace app\core\utils;

use app\core\model\BaseModel;
use app\core\Template;
use app\core\view\View;
use function app\core\helper\model_path;
use function app\core\helper\view_path;
use ErrorException;

class LoadUtils
{
    /**
     * Load một model.
     * @param string $model_name Tên model cần load
     * @return BaseModel đối tượng model tương ứng nếu load thành công.
     */
    public static function load_model($model_name): BaseModel
    {
        if (!file_exists(model_path($model_name . '.php'))) {
            // Handle error missing model file
            echo model_path($model_name . '.php');
            throw new ErrorException("File model '" . $model_name . ".php' không tồn tại trong thư mục " . model_path() . ". Đảm bảo bạn đã đặt đúng tên view tương ứng.");
        }
        require_once model_path($model_name . '.php');

        $model_name = "app\core\model\\" . $model_name;

        if (!class_exists($model_name)) {
            // Handle error model class not exist
            throw new ErrorException("Lớp model '" . $model_name . ".php' không tồn tại! Kiểm tra lại cách đặt tên của lớp hoặc đảm bảo bạn đã định nghĩa lớp này.");
        }
        $model = new $model_name();

        return $model;
    }

    /** 
     * Load một view.
     * @param string $view_name Tên view cần load.
     */
    public static function load_view($view_name, $data = [])
    {
        if (!empty(View::$data_share)) {
            $data = array_merge($data, View::$data_share);
        }

        if (!file_exists(view_path($view_name . ".php"))) {
            // Handle error if view file not exist
            throw new ErrorException("File view '" . $view_name . ".php' không tồn tại trong thư mục " . view_path() . ". Đảm bảo bạn đã đặt đúng tên view tương ứng.");
        }

        require_once view_path($view_name . ".php");
    }

    public static function render_block($view_name, $data = [])
    {

        if (!empty(View::$data_share)) {
            $data = array_merge($data, View::$data_share);
        }

        if (!file_exists(view_path($view_name . ".php"))) {
            // Handle error if view file not exist
            throw new ErrorException("File view '" . $view_name . ".php' không tồn tại trong thư mục " . view_path() . ". Đảm bảo bạn đã đặt đúng tên view tương ứng.");
        }

        $content = file_get_contents(view_path($view_name . ".php"));

        $template = new Template();
        $template->run($content, $data);
    }
}
