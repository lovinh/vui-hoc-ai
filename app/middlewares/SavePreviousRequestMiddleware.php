<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\Session;

class SavePreviousRequestMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next, $params = [])
    {
        // Session::put('previous_request', $request->full_url());
    }
}
