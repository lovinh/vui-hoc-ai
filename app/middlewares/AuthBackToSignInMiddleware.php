<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\Session;

use function app\core\helper\redirect;
use function app\core\helper\route_url;

class AuthBackToSignInMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next)
    {
        if (empty(Session::get('user_id'))) {
            redirect(route_url('user.auth.index'));
            exit;
        }
    }
}
