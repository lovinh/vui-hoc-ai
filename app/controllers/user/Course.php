<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\CourseModel;
use app\core\view\View;

use function app\core\helper\load_model;

class Course extends BaseController
{
    public function index()
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        $data = [
            'page-title' => "Vui hoc AI - All Courses",
            'course_result_title' => "All courses",
            'view' => 'user/course',
            'courses' => $course_model->get_courses(),
            'model' => $course_model
        ];
        return View::render('layouts/user_layout', $data);
    }

    public function search_by_subject(string $subject)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        $data = [
            'page-title' => "Vui Hoc AI - " . $subject . " Courses",
            'course_result_title' => "All '" . ucfirst($subject) . "' courses",
            'view' => 'user/course',
            'courses' => $course_model->get_courses_by_subject($subject),
            'model' => $course_model
        ];
        return View::render('layouts/user_layout', $data);
    }

    public function search_by_name()
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        $course_name = urldecode($this->request->get_fields_data()['search_input']);
        $data = [
            'page-title' => "Vui Hoc AI - Search Course " . $course_name,
            'course_result_title' => "All courses match '" . $course_name . "'",
            'view' => 'user/course',
            'courses' => $course_model->get_courses_by_search($course_name),
            'model' => $course_model
        ];
        return View::render('layouts/user_layout', $data);
    }

    public function detail($id)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');

        if ($course_model->is_have_course($id)) {
        }

        $course_detail = [
            "id" => $id,
            "name" => $course_model->get_course_name($id),
            "description" => $course_model->get_course_description($id),
            "author" => $course_model->get_course_author($id),
            "price" => $course_model->get_course_price($id),
            "last_update" => $course_model->get_course_last_update_time($id),
            "reviews" => $course_model->get_course_review($id),
            "subject" => $course_model->get_course_subject($id),
            "rate" => $course_model->get_course_reviews_total_rate($id),
            "banner" => $course_model->get_course_banner_uri($id),
            'lesson' => $course_model->get_course_lessons($id),
            'status' => $course_model->get_course_status($id)
        ];

        $data = [
            'page-title' => $course_detail['name'] . ' - Course - Vui Hoc AI',
            'view' => 'user/course_detail',
            'model' => $course_detail
        ];
        return View::render('layouts/user_layout', $data);
    }
}
