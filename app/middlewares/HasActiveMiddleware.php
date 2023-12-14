<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\model\user\CourseModel;
use app\core\model\user\PaymentModel;
use app\core\model\user\UserModel;
use app\core\Route;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class HasActiveMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next, $params = [])
    {
        /**
         * @var UserModel
         */
        $user_model = load_model('user\UserModel');

        $user_id = $user_model->get_user_id_from_session();

        if (empty($user_id) || $user_model->get_user_status($user_id) != 'active') {
            redirect(route_url('user.auth.validate_email'));
            exit;
        }
        return $next($request);
    }
}
