<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\CourseModel;
use app\core\view\View;

use function app\core\helper\load_model;

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

    public function payment(int $id)
    {
        
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
