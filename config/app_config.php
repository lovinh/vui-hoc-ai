<?php

/**
 * @var array
 */

use app\core\middleware\user\SavePreviousRequestMiddleware;
use app\core\service\AppServiceProvider;
use PHPMailer\PHPMailer\PHPMailer;

$app_config = [
    "debug_mode" => true,
    "service" => [],
    "global_middleware" => [
        SavePreviousRequestMiddleware::class,
    ],
    "boot" => [AppServiceProvider::class,],
    "mail" => [
        "host" => "smtp.gmail.com",
        "secure" => PHPMailer::ENCRYPTION_STARTTLS,
        "port" => 587,
        "username" => "thanhvinh051202@gmail.com",
        "password" => "uqnd jjkh tmdv kjqr",
        "email_from" => "thanhvinh051202@gmail.com",
        "name_from" => "Vui Hoc AI"
    ]
];
