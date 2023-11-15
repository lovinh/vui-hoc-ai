<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\model\user\CourseLessonSectionModel;
use app\core\model\user\CourseModel;
use app\core\model\user\LessonModel;
use app\core\model\user\ProgressModel;
use app\core\model\user\SectionModel;
use app\core\model\user\UserModel;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class LearningCourse extends BaseController
{
    public function index($course_id)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        /**
         * @var LessonModel
         */
        $lesson_model = load_model('user\LessonModel');
        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        /**
         * @var ProgressModel
         */
        $progress_model = load_model('user\ProgressModel');
        /**
         * @var UserModel
         */
        $user_model = load_model('user\UserModel');

        $user_id = $user_model->get_user_id_from_session();

        $lessons = $lesson_model->get_lessons($course_id);

        $lesson_section = [];

        foreach ($lessons as $key => $lesson) {
            $lesson_section[$key] = [
                "lesson_name" => $lesson['lesson_title'],
                "lesson_id" => $lesson['lesson_id'],
                "sections" => $section_model->get_sections($lesson['lesson_id']),
            ];
        }

        $course_name = $course_model->get_course_name($course_id);

        $nearest_progress = $progress_model->get_nearest_progress($user_id, $course_id);

        $begin = false;

        if (empty($nearest_progress)) {
            $lesson_id =  $lesson_model->get_first_lesson_id($course_id);
            $nearest_progress = [
                "lesson_id" => $lesson_id,
                "section_id" => $section_model->get_first_section_id($lesson_id),
            ];
            $begin = true;
        } else {
            $nearest_progress = [
                "lesson_id" => $nearest_progress['process_lesson_id'],
                "section_id" => $nearest_progress['process_section_id'],
            ];
        }

        $model = [
            'user_id' => $user_id,
            'course_name' => $course_name,
            'course_id' => $course_id,
            'description' => $course_model->get_course_description($course_id),
            'author' => $course_model->get_course_author($course_id),
            'last_update' => $course_model->get_course_last_update_time($course_id),
            'subject' => $course_model->get_course_subject($course_id),
            'banner' => $course_model->get_course_banner_uri($course_id),
            'lesson' => $lesson_section,
            'related_course' => [],
            'nearest_progress' => $nearest_progress,
            'begin' => $begin,
            'process' => $progress_model,
        ];

        $data = [
            'page-title' => $course_name . "- Learning - Vui Hoc AI",
            'view' => 'user/learning/introduction',
            'model' => $model,
        ];
        return View::render('layouts/user_learning_layout', $data);
    }

    public function learn_section(int $course_id, int $lesson_id, int $section_id)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        /**
         * @var LessonModel
         */
        $lesson_model = load_model('user\LessonModel');
        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        /**
         * @var UserModel
         */
        $user_model = load_model('user\UserModel');
        /**
         * @var ProgressModel
         */
        $progress_model = load_model('user\ProgressModel');

        $lessons = $lesson_model->get_lessons($course_id);

        $lesson_section = [];

        foreach ($lessons as $key => $lesson) {
            $lesson_section[$key] = [
                "lesson_name" => $lesson['lesson_title'],
                "lesson_id" => $lesson['lesson_id'],
                "sections" => $section_model->get_sections($lesson['lesson_id'])
            ];
        }

        $course_name = $course_model->get_course_name($course_id);

        $section_content = $section_model->get_section($lesson_id, $section_id);
        if (empty($section_content)) {
            redirect(route_url('user.learning.intro', ['id' => $course_id]));
        }

        $user_id = $user_model->get_user_id_from_session();


        $model = [
            'course_name' => $course_name,
            'course_id' => $course_id,
            'lesson_id' => $lesson_id,
            'section_id' => $section_id,
            'section_name' => $section_content['section_name'],
            'section_content' => $section_content['section_content'],
            'lesson' => $lesson_section,
        ];

        $progress_model->add_progess($user_id, $course_id, $lesson_id, $section_id);

        $data = [
            'page-title' => $course_model->get_course_name($course_id) . "- Learning - Vui Hoc AI",
            'view' => 'user/learning/detail',
            'model' => $model,
        ];
        return View::render('layouts/user_learning_layout', $data);
    }

    public function progress(int $course_id)
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        /**
         * @var LessonModel
         */
        $lesson_model = load_model('user\LessonModel');
        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        /**
         * @var ProgressModel
         */
        $progress_model = load_model('user\ProgressModel');
        /**
         * @var UserModel
         */
        $user_model = load_model('user\UserModel');

        $user_id = $user_model->get_user_id_from_session();

        $lessons = $lesson_model->get_lessons($course_id);

        $lesson_section = [];

        foreach ($lessons as $key => $lesson) {
            $lesson_section[$key] = [
                "lesson_name" => $lesson['lesson_title'],
                "lesson_id" => $lesson['lesson_id'],
                "sections" => $section_model->get_sections($lesson['lesson_id']),
                "complete" => $progress_model->get_lesson_precent_complete($user_id, $course_id, $lesson['lesson_id']),
            ];
        }

        $course_name = $course_model->get_course_name($course_id);

        $nearest_progress = $progress_model->get_nearest_progress($user_id, $course_id);

        $begin = false;

        if (empty($nearest_progress)) {
            $lesson_id =  $lesson_model->get_first_lesson_id($course_id);
            $nearest_progress = [
                "lesson_id" => $lesson_id,
                "section_id" => $section_model->get_first_section_id($lesson_id),
            ];
            $begin = true;
        } else {
            $nearest_progress = [
                "lesson_id" => $nearest_progress['process_lesson_id'],
                "section_id" => $nearest_progress['process_section_id'],
            ];
        }

        $model = [
            'user_id' => $user_id,
            'course_name' => $course_name,
            'course_id' => $course_id,
            'author' => $course_model->get_course_author($course_id),
            'last_update' => $course_model->get_course_last_update_time($course_id),
            'subject' => $course_model->get_course_subject($course_id),
            'lesson' => $lesson_section,
            'related_course' => [],
            'nearest_progress' => $nearest_progress,
            'begin' => $begin,
            'process' => $progress_model,
            'complete' => $progress_model->get_course_precent_complete($user_id, $course_id),
        ];

        $data = [
            'page-title' => $course_model->get_course_name($course_id) . "- Progress - Vui Hoc AI",
            'view' => 'user/learning/progress',
            'model' => $model,
        ];
        return View::render('layouts/user_learning_layout', $data);
    }
    
    public function note()
    {
        
    }
}
