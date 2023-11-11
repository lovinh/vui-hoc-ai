<?php

/**
 * @var array
 */


use app\core\service\AppServiceProvider;

$app_config = [
    "debug_mode" => true,
    "service" => [],
    "global_middleware" => [],
    "boot" => [AppServiceProvider::class,],
];
