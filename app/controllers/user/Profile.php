<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\AuthorModel;
use app\core\model\user\CourseModel;
use app\core\model\user\PaymentModel;
use app\core\model\user\UserModel;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\load_model;

class Profile extends BaseController
{
    public function index()
    {
        /**
         * @var UserModel
         */
        $user_model = load_model('user\UserModel');
        /**
         * @var PaymentModel
         */
        $payment_model = load_model('user\PaymentModel');

        $user_id = $user_model->get_user_id_from_session();

        $role = $user_model->get_user_role($user_id);

        if ($role == "user" && $payment_model->is_user_learner($user_id)) {
            $role = "learner";
        }

        $model = [
            'user_id' => $user_id,
            'user_first_name' => $user_model->get_user_first_name($user_id),
            'user_last_name' => $user_model->get_user_last_name($user_id),
            'user_name' => $user_model->get_user_name($user_id),
            'user_email' => $user_model->get_user_email($user_id),
            'user_status' => $user_model->get_user_status($user_id),
            'user_avatar' => $user_model->get_user_avatar($user_id),
            'user_role' => $role,
            'user_information' => $user_model->get_user_information($user_id),
        ];
        $data = [
            "page-title" => "User Profile - Vui Hoc AI",
            'page' => "profile",
            "view" => "user/profile/index",
            'model' => $model,
        ];

        return View::render("layouts/user_layout", $data);
    }
}
