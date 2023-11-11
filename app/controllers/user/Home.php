<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\AuthorModel;
use app\core\model\user\CourseModel;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\load_model;

class Home extends BaseController
{
    public function index()
    {
        /**
         * @var AuthorModel
         */
        $author_model = load_model('user\AuthorModel');

        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');

        $data = [
            "page-title" => "Vui học AI - Trang chủ",
            "view" => "user/home",
            "author" => $author_model,
            "newest_courses" => $course_model->get_newest_courses()
        ];

        return View::render("layouts/user_layout", $data);
    }
}
