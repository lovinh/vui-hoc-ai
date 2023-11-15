<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\Session;

use function app\core\helper\load_model;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class RedirectIfEnrolledMiddleware implements IMiddleware
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
                    redirect(route_url('user.learning.intro', ['id' => $params[0]]));
                    exit;
                }
            }
        return $next($request);
    }
}
