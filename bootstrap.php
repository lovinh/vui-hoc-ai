<?php
// Load PHPMailer
require_once "vendor/autoload.php";

define('_DIR_ROOT', str_replace('\\', '/', __DIR__));
// Handle http root
// Get web url protocol
if (!empty($_SERVER["HTTPS"]) &&  $_SERVER["HTTPS"] = "on") {
    $web_root = "https://" . $_SERVER["HTTP_HOST"];
} else {
    $web_root = "http://" . $_SERVER["HTTP_HOST"];
}

// Get web root folder
$document_root = implode('/', explode('/', $_SERVER["DOCUMENT_ROOT"]));
$folder = ltrim(str_replace(strtolower($document_root), '', strtolower(_DIR_ROOT)), '/');
$web_root = $web_root . '/' . $folder;

// define constant variable for web root
define('_WEB_ROOT', $web_root);

// Load config. 
$config_dir = scandir("config");
if (!empty($config_dir)) {
    foreach ($config_dir as $config_file) {
        if ($config_file != '.' && $config_file != ".." && file_exists('config/' . $config_file)) {
            require_once "config/" . $config_file;
        }
    }
}

// Check for debug mode
if (!$app_config["debug_mode"]) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);
}



// Load all helper
// Load core helper
$core_helper_dir = scandir("core/helpers");
if (!empty($core_helper_dir)) {
    foreach ($core_helper_dir as $helper_file) {
        if ($helper_file != '.' && $helper_file != ".." && file_exists('core/helpers/' . $helper_file)) {
            require_once "core/helpers/" . $helper_file;
        }
    }
}

// Load user-defined helpers
$helper_dir = scandir("app/helpers");
if (!empty($helper_dir)) {
    foreach ($helper_dir as $helper_file) {
        if ($helper_file != '.' && $helper_file != ".." && file_exists('app/helpers/' . $helper_file)) {
            require_once "app/helpers/" . $helper_file;
        }
    }
}


// Load session
require_once "core/Session.php";

// Load middleware
require_once "core/BaseMiddleware.php";


// Load routing
require_once "core/Route.php";

// Load app
require_once "app/App.php";

// Load database connection
// Check configuration
if (!empty($database_config)) {
    require_once "core/Connection.php";
    require_once "core/QueryBuilder.php";
    require_once "core/Database.php";
    require_once "core/DB.php";
}

// Load app core

// Load Interface
$interface_dir = scandir("core/interfaces");
if (!empty($interface_dir)) {
    foreach ($interface_dir as $interface_file) {
        if ($interface_file != '.' && $interface_file != ".." && file_exists('core/interfaces/' . $interface_file)) {
            require_once "core/interfaces/" . $interface_file;
        }
    }
}
// Load ServiceProvider
require_once "core/ServiceProvider.php";

// Load View
require_once "core/View.php";

// Load LoadUtils
require_once "core/LoadUtils.php";

// Load base model
require_once "core/BaseModel.php";

// Load template
require_once "core/Template.php";

// Load base controller
require_once "core/BaseController.php";

// Load DefinedRules
require_once "core/DefinedRules.php";

// Load validator
require_once "core/Validator.php";

// Load custom rule (if need)
if ($validate_config["apply_custom_rule"]) {
    $rule_dir = scandir("app/rules");
    if (!empty($rule_dir)) {
        foreach ($rule_dir as $rule_file) {
            if ($rule_file != '.' && $rule_file != ".." && file_exists('app/rules/' . $rule_file)) {
                require_once "app/rules/" . $rule_file;
            }
        }
    }
}

// Load request, response
require_once "core/Request.php";
require_once "core/Response.php";

use function app\core\helper\app_path;

// Load middleware
$path = "middlewares";
$middleware_dir = scandir(app_path($path));
if (!empty($middleware_dir)) {
    foreach ($middleware_dir as $middleware_file) {
        if ($middleware_file != '.' && $middleware_file != ".." && file_exists(app_path($path . "/" . $middleware_file))) {
            require_once app_path($path . "/" . $middleware_file);
        }
    }
}

// Load custom controllers


$path = "controllers";
$stack_load = scandir(app_path($path));
while (!empty($stack_load)) {
    $consider = array_pop($stack_load);
    if ($consider == '.' || $consider == '..')
        continue;
    if (is_file(app_path($path . "/" . $consider))) {
        include_once app_path($path . "/" . $consider);
        continue;
    }
    if (is_dir(app_path($path . "/" . $consider))) {
        $sub_dir = scandir(app_path($path . "/" . $consider));
        foreach ($sub_dir as $value) {
            if ($value == "." || $value == "..") continue;
            $stack_load[] = $consider . "/" . $value;
        }
        continue;
    }
}

// Load Route

// Load Services and custom class
$service_dir = scandir(app_path("core"));
if (!empty($service_dir)) {
    foreach ($service_dir as $service_file) {
        if ($service_file != "." && $service_file != ".." && file_exists(app_path("core/" . $service_file))) {
            require_once app_path("core/" . $service_file);
        }
    }
}

// Load FileUpload

require_once "core/FileUpload.php";
