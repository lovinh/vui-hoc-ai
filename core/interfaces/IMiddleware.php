<?php

namespace app\core\middleware;

use app\core\http_context\Request;

interface IMiddleware
{
    public function handle(Request $request, callable $next, $params = []);
}
