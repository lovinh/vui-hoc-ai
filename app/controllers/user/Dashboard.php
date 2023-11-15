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

class Dashboard extends BaseController
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
        /**
         * @var PaymentModel
         */
        $payment_model = load_model('user\PaymentModel');

        $user_id = $user_model->get_user_id_from_session();

        $courses_registered_id = $payment_model->get_courses_registered($user_id);

        $model = [
            'courses' => $courses_registered_id,
        ];
        
        $data = [
            'page-title' => 'Dashboard - Vui Hoc AI',
            'view' => 'user/dashboard',
            'user_id' => $user_id,
            'model' => $model
        ];

        return View::render("layouts/user_layout", $data);
    }
}
