<?php

namespace app\core\helper;

use app\core\http_context\Response;
use InvalidArgumentException;

define('BYTE_KILOBYTE', 1000001);
define('BYTE_MEGABYTE', 1000002);
define('BYTE_GIGABYTE', 1000003);
define('BYTE_TERABYTE', 1000004);
define('KILOBYTE_BYTE', 1000005);
define('MEGABYTE_BYTE', 1000006);
define('GIGABYTE_BYTE', 1000007);
define('TERABYTE_BYTE', 1000008);

if (!function_exists("to_slug")) {
    function to_slug($value)
    {
        return $value;
    }
}
/**
 * Chuyển hướng đến đường dẫn đích
 * @param string $url_location Đường dẫn địa chỉ chuyển hướng tới
 * @param int $code mã trạng thái phản hồi. Mặc định là 301
 */
function redirect(string $url_location, int $code = 301)
{
    http_response_code($code);
    header('Location: ' . $url_location);
    exit;
}

function response()
{
    return new Response();
}
