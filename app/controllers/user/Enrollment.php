<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\CourseModel;
use app\core\model\user\MessageModel;
use app\core\model\user\MessageUserModel;
use app\core\model\user\PaymentModel;
use app\core\model\user\ProgressModel;
use app\core\model\user\UserModel;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\load_view;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class Enrollment extends BaseController
{
    public function index(int $id)
    {
        /**
         * @var CourseModel
         */
        $model = load_model('user\CourseModel');
        $data = [
            "page-title" => $model->get_course_name($id) . " - Enrollment - Vui Hoc AI",
            "view" => 'user/enroll_course_index',
            "model" => [
                "id" => $id,
                "name" => $model->get_course_name($id),
                "author" => $model->get_course_author($id),
                "last_update" => $model->get_course_last_update_time($id),
                "subject" => $model->get_course_subject($id),
                "author_avt" => $model->get_course_author_avatar($id),
                "price" => $model->get_course_price($id),
                "banner" => $model->get_course_banner_uri($id),
                "thumbnail" => $model->get_course_thumbnail($id),
            ]
        ];
        return View::render('layouts/user_layout', $data);
    }

    public function payment(int $course_id)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        /**
         * @var UserModel
         */
        $model = load_model('user\UserModel');
        /**
         * @var PaymentModel
         */
        $payment_model = load_model('user\PaymentModel');
        /**
         * @var MessageModel
         */
        $message_model = load_model('user\MessageModel');
        /**
         * @var MessageUserModel
         */
        $message_user_model = load_model('user\MessageUserModel');
        $user_id = $model->get_user_id_from_session();
        $payment_model->add_payment($course_id, $user_id);
        $message_model->add_message('user_msg', "Congratulation! You have enrolled " . $course_model->get_course_name($course_id) . "! Feel free to learn now!", $user_id);
        $message_id = $message_model->get_last_message_id();
        $message_user_model->add_message_user($message_id, $user_id);
        redirect(route_url('user.enroll.payment_status', ['id' => $course_id]));
        exit;
    }

    public function payment_status(int $id)
    {
        /**
         * @var CourseModel
         */
        $model = load_model('user\CourseModel');
        $data = [
            "page-title" => $model->get_course_name($id) . " - Enrollment - Vui Hoc AI",
            "view" => 'user/enroll_course_status',
            "model" => [
                "id" => $id,
                "name" => $model->get_course_name($id),
                "author" => $model->get_course_author($id),
                "last_update" => $model->get_course_last_update_time($id),
                "subject" => $model->get_course_subject($id),
                "author_avt" => $model->get_course_author_avatar($id),
                "price" => $model->get_course_price($id),
                "banner" => $model->get_course_banner_uri($id),
                "thumbnail" => $model->get_course_thumbnail($id),
            ]
        ];
        return View::render('layouts/user_layout', $data);
    }
}
