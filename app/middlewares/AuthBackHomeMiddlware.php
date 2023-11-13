<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\Session;

use function app\core\helper\redirect;
use function app\core\helper\route_url;

class AuthBackHomeMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next)
    {
        if (!empty(Session::get('user'))) {
            redirect(route_url('user.home.index'));
            exit;
        }
    }
}
