<?php

namespace app\core\helper;
/**
 * Trả về đường dẫn tuyệt đối của ứng dụng.
 * @param string $relative_path đường dẫn tương đối đến một file. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn gốc của ứng dụng kèm theo đường dẫn bổ sung (nếu có)
 */
function app_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/app' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}
/**
 * Trả về đường dẫn tuyệt đối đến thư mục config của dự án.
 * @param string $relative_path đường dẫn tương đối đến một file. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn gốc tới phần config kèm theo đường dẫn bổ sung (nếu có)
 */
function config_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/config' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}
/**
 * Trả về đường dẫn tuyệt đối đến thư mục public của dự án.
 * @param string $relative_path đường dẫn tương đối đến một file. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn gốc tới phần public kèm theo đường dẫn bổ sung (nếu có)
 */
function public_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/public' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}
/**
 * Trả về đường dẫn tuyệt đối đến thư mục models của dự án.
 * @param string $relative_path đường dẫn tương đối đến một file model. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn gốc tới phần models kèm theo đường dẫn bổ sung (nếu có)
 */
function model_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/app/models' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}

/**
 * Trả về đường dẫn tuyệt đối đến thư mục views của dự án.
 * @param string $relative_path đường dẫn tương đối đến một file view. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn gốc tới phần views kèm theo đường dẫn bổ sung (nếu có)
 */
function view_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/app/views' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}

/**
 * Trả về thông tin về đường dẫn hiện tại của yêu cầu từ người dùng
 * @param string $relative_path đường dẫn tương đối đến một file. Khi đó hàm trả về đường dẫn kèm theo phần đường dẫn tương đối.
 * @return string Đường dẫn hiện tại của yêu cầu từ người dùng
 */
function path_info($relative_path = '')
{
    $path = "/";
    if (!empty($_SERVER["PATH_INFO"])) {
        $path = $_SERVER["PATH_INFO"];
    }
    return $path . (!empty($relative_path) ? ('/' . $relative_path) : false);
}

function files_path($relative_path = '')
{
    if (!empty($relative_path)) {
        $relative_path = str_replace('\\', '/', $relative_path);
    }
    return _DIR_ROOT . '/public/files' . (!empty($relative_path) ? ('/' . $relative_path) : false);
}
