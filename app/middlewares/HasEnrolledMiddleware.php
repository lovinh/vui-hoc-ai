<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\model\user\CourseModel;
use app\core\model\user\PaymentModel;
use app\core\Route;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class HasEnrolledMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next, $params = [])
    {
        /**
         * @var PaymentModel
         */
        $course_model = load_model('user\PaymentModel');
        $user_id_list = $course_model->get_users_id($params[0]);
        if (!empty($user_id_list))
            foreach ($user_id_list as $user) {
                if (hash('sha256', $user['payment_user_id']) == Session::get('user')) {
                    return $next($request);
                }
            }
        redirect(route_url('user.home.index'));
    }
}
