<?php

namespace app\core\controller\author;

use app\core\controller\BaseController;
use app\core\http_context\Request;
use app\core\http_context\Response;
use app\core\model\author\CourseModel as AuthorCourseModel;
use app\core\model\author\CourseSubjectModel;
use app\core\model\author\CourseUpdateModel;
use app\core\model\author\LessonModel;
use app\core\model\author\SectionModel;
use app\core\model\author\SubjectModel;
use app\core\model\user\CourseModel;
use app\core\Session;
use app\core\utils\FileUpload;
use app\core\view\View;

use function app\core\helper\redirect;
use function app\core\helper\response;
use function app\core\helper\route_url;

class Course extends BaseController
{
    private $data = [];
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->data['title'] = "My Courses";
        $this->data['page'] = "author/course/index";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';

        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        $this->data['courses'] = $course_model->get_courses_overview();
        $this->data['message'] = Session::flash('message');
        return View::render('layouts/author_layout', $this->data);
    }

    public function available()
    {
        $this->data['title'] = "My Available Courses";
        $this->data['page'] = "author/course/index";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';

        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        $this->data['courses'] = $course_model->get_available_courses_overview();
        return View::render('layouts/author_layout', $this->data);
    }

    public function draft()
    {
        $this->data['title'] = "My Draft Courses";
        $this->data['page'] = "author/course/index";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';

        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        $this->data['courses'] = $course_model->get_draft_courses_overview();
        return View::render('layouts/author_layout', $this->data);
    }

    public function detail(string $course_id)
    {
        $this->data['title'] = "My Draft Courses";
        $this->data['page'] = "author/course/detail";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';

        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var CourseSubjectModel
         */
        $course_subject_model = $this->get_model('author\CourseSubjectModel');
        /**
         * @var CourseUpdateModel
         */
        $course_update_model = $this->get_model('author\CourseUpdateModel');
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        $this->data['course'] = $course_model->get_course_overview($course_id);
        $this->data['course']['course_subject'] = $course_subject_model->get_course_subject($course_id);
        $this->data['course']['course_last_update'] = $course_update_model->get_course_last_update_time($course_id);
        $this->data['course']['course_updates'] = $course_update_model->get_update_course($course_id);
        $this->data['course']['course_lessons'] = $lesson_model->get_lessons($course_id);
        $this->data['course']['course_sections'] = [];
        foreach ($this->data['course']['course_sections'] as $key => $value) {
            $this->data['course']['course_sections'][$value['lesson_id']] = $section_model->get_sections($value['lesson_id']);
        }
        $this->data['error'] = Session::flash('error');
        return View::render('layouts/author_layout', $this->data);
    }

    public function new()
    {
        $this->data['title'] = "Create New Course";
        $this->data['page'] = "author/course/create";
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        $this->data['head'] = 'author/_head/create';
        $this->data['script'] = 'author/_script/create';
        $errors = Session::flash('errors');
        $error = Session::flash('error') ?? null;
        $this->data['errors'] = $errors;
        $this->data['error'] = $error;
        $this->data['current'] = Session::flash('current');
        return View::render('layouts/author_layout', $this->data);
    }

    public function create()
    {
        $this->data['title'] = "Creating New Course";
        $this->data['page'] = "author/course/create";
        $this->data['head'] = 'author/_head/create';
        $this->data['script'] = 'author/_script/create';

        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');

        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }

        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);

        $this->request->validate->field('course_name')
            ->required("Course name cannot be empty!");

        $this->request->validate->field('course_subject')
            ->required("Course subject cannot be empty!")
            ->max_char(256, "Course subject cannot be longer than 256 charactors!")
            ->like("/^[^`~!@#$%^&*()_+={}\[\]|\\:;“’<,>.?๐฿]*$/s", "Course subject cannot contain special charactors!");

        $this->request->validate->field('course_price')
            ->required("Course price cannot be empty!")
            ->numeric("Coure price cannot contain non-digit value!")
            ->integer("Coure price must be an interger value!")
            ->min(0, "Course price cannot be smaller than 0");

        $this->request->validate->field('course_thumbnail')
            ->image('Course thumbnail must be an image file!')
            ->max_byte('5242880', "Max size of the course thumbnail file is 5MB!");

        $this->request->validate->field('course_banner')
            ->image('Course thumbnail must be an image file!')
            ->max_byte('5242880', "Max size of the course banner file is 5MB!");

        if ($this->request->validate->is_error()) {
            Session::flash('errors', $this->request->validate->get_first_error());
            Session::flash('current', $this->request->validate->get_fields_data());
            $this->response->redirect(route_url('author.course.new'));
            exit;
        } else {
            /**
             * @var SubjectModel
             */
            $subject_model = $this->get_model('author\SubjectModel');
            /**
             * @var CourseSubjectModel
             */
            $course_subject_model = $this->get_model('author\CourseSubjectModel');
            $check = true;
            $validated_data = $this->request->validate->get_fields_data();
            $course_name = $validated_data['course_name'];
            $course_description = $validated_data['course_description'];
            $course_thumbnail = $validated_data['course_thumbnail'];
            if ($course_thumbnail instanceof FileUpload) {
                $course_thumbnail = $course_thumbnail->store();
            }
            $course_banner = $validated_data['course_banner'];
            if ($course_banner instanceof FileUpload) {
                $course_banner = $course_banner->store();
            }
            $course_price = $validated_data['course_price'];
            $course_subject = $validated_data['course_subject'];
            $check = $course_model->insert(
                $course_name,
                html_entity_decode($course_description),
                $course_thumbnail,
                $course_banner,
                $course_price
            );
            if ($check) {
                if (empty($subject_model->get_subject_by_name($course_subject))) {
                    $check = $subject_model->insert($course_subject);
                }
                if ($check) {
                    $check = $course_subject_model->insert($subject_model->get_last_subject_id(), $course_model->get_last_course_id());
                }
            }
            if ($check) {
                Session::flash('message', "Create new course succesfully!");
                redirect(route_url("author.course.index"));
                exit;
            } else {
                Session::flash('error', "Errors when create new course. Please try again!");
                Session::flash('current', $this->request->validate->get_fields_data());
                $this->response->redirect(route_url('author.course.new'));
                exit;
            }
        }
    }

    public function edit(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var CourseSubjectModel
         */
        $course_subject_model = $this->get_model('author\CourseSubjectModel');
        $course = $course_model->get_course_overview($course_id);
        $course_subject = $course_subject_model->get_course_subject($course_id);
        $this->data['title'] = "Edit Course " . $course['course_name'];
        $this->data['page'] = "author/course/edit";
        $this->data['head'] = 'author/_head/create';
        $this->data['script'] = 'author/_script/index';
        $this->data['course'] = $course;
        $this->data['course']['course_id'] = $course_id;
        $this->data['course']['course_subject'] = $course_subject;
        $errors = Session::flash('errors');
        $error = Session::flash('error') ?? null;
        $this->data['errors'] = $errors;
        $this->data['error'] = $error;
        $this->data['current'] = Session::flash('current');
        $this->data['message'] = Session::flash('message');
        return View::render('layouts/author_layout', $this->data);
    }

    public function editing(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');

        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }

        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);

        $this->request->validate->field('course_name')
            ->required("Course name cannot be empty!");

        $this->request->validate->field('course_subject')
            ->required("Course subject cannot be empty!")
            ->max_char(256, "Course subject cannot be longer than 256 charactors!")
            ->like("/^[^`~!@#$%^&*()_+={}\[\]|\\:;“’<,>.?๐฿]*$/s", "Course subject cannot contain special charactors!");

        $this->request->validate->field('course_price')
            ->required("Course price cannot be empty!")
            ->numeric("Coure price cannot contain non-digit value!")
            ->integer("Coure price must be an interger value!")
            ->min(0, "Course price cannot be smaller than 0");

        $this->request->validate->field('course_thumbnail')
            ->image('Course thumbnail must be an image file!')
            ->max_byte('5242880', "Max size of the course thumbnail file is 5MB!");

        $this->request->validate->field('course_banner')
            ->image('Course thumbnail must be an image file!')
            ->max_byte('5242880', "Max size of the course banner file is 5MB!");

        if ($this->request->validate->is_error()) {
            Session::flash('errors', $this->request->validate->get_first_error());
            Session::flash('current', $this->request->validate->get_fields_data());
            $this->response->redirect(route_url('author.course.edit', ["course_id" => $course_id]));
            exit;
        } else {
            /**
             * @var SubjectModel
             */
            $subject_model = $this->get_model('author\SubjectModel');
            /**
             * @var CourseSubjectModel
             */
            $course_subject_model = $this->get_model('author\CourseSubjectModel');
            /**
             * @var CourseUpdateModel
             */
            $course_update_model = $this->get_model('author\CourseUpdateModel');
            $check = true;
            $validated_data = $this->request->validate->get_fields_data();
            $course_name = $validated_data['course_name'];
            $course_description = $validated_data['course_description'];
            $course_thumbnail = $validated_data['course_thumbnail'];
            $course_update_description = $validated_data['course_update_description'];
            if ($course_thumbnail instanceof FileUpload) {
                $course_thumbnail = $course_thumbnail->store();
            } else {
                $course_thumbnail = null;
            }
            $course_banner = $validated_data['course_banner'];
            if ($course_banner instanceof FileUpload) {
                $course_banner = $course_banner->store();
            } else {
                $course_banner = null;
            }
            $course_price = $validated_data['course_price'];
            $course_subject = $validated_data['course_subject'];
            $check = $course_model->update(
                $course_id,
                $course_name,
                html_entity_decode($course_description),
                $course_thumbnail,
                $course_banner,
                $course_price,
            );
            if ($check) {
                if (empty($subject_model->get_subject_by_name($course_subject))) {
                    $check = $subject_model->insert($course_subject);
                }
                if ($check) {
                    $check = $course_subject_model->update($course_subject_model->get_course_subject_id($course_id), $course_id, $subject_model->get_subject_by_name($course_subject)['subject_id']);
                    if ($check) {
                        $check = $course_update_model->insert($course_id, html_entity_decode($course_update_description));
                    }
                }
            }
            if ($check) {
                Session::flash('message', "Edit course succesfully!");
                redirect(route_url("author.course.detail", ["course_id" => $course_id]));
                exit;
            } else {
                Session::flash('error', "Errors when edit course. Please try again!");
                Session::flash('current', $this->request->validate->get_fields_data());
                $this->response->redirect(route_url('author.course.edit', ["course_id" => $course_id]));
                exit;
            }
        }
    }

    public function deleting(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id))) {
            return response([], 403, "Premission Denied");
        }
        $check = $course_model->delete($course_id);
        if ($check) {
            Session::flash('message', "Delete course succesfully!");
            redirect(route_url("author.course.index"));
            exit;
        } else {
            Session::flash('error', "Errors when delete the course. Please try again!");
            $this->response->redirect(route_url('author.course.detail', ["course_id" => $course_id]));
            exit;
        }
    }
}
