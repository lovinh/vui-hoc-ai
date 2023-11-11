<?php

namespace app\core\view;

use app\core\http_context\Response;
use app\core\Template;
use InvalidArgumentException;

use function app\core\helper\response;
use function app\core\helper\view_path;

class View
{
    public static $data_share = [];

    public static function share($key, $data)
    {
        self::$data_share[$key] = $data;
    }

    public static function get_data_share(string $key)
    {
        if (empty($key)) {
            throw new InvalidArgumentException("VIEW KEY ERROR: Key không được để trống!");
        }
        if (!array_key_exists($key, self::$data_share)) {
            return null;
        }
        return self::$data_share[$key];
    }

    public static function render($view_name, $data = [])
    {
        $content_view = file_get_contents(view_path("$view_name.php"));
        $template = new Template();
        ob_start();
        $template->run($content_view, $data);
        $content = ob_get_contents();
        ob_end_clean();
        return response()->set_content($content);
    }
}
