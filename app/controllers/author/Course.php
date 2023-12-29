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
    private $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = View::get_data_share('user_id');
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
        $this->data['courses'] = $course_model->get_courses_overview(user_id: $this->user_id);
        $this->data['message'] = Session::flash('message');
        $this->data['courses_title'] = "My Courses List";
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
        $this->data['courses'] = $course_model->get_available_courses_overview($this->user_id);
        $this->data['courses_title'] = "My Available Courses";
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
        $this->data['courses'] = $course_model->get_draft_courses_overview($this->user_id);
        $this->data['courses_title'] = "My Draft Courses";
        return View::render('layouts/author_layout', $this->data);
    }

    public function detail(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
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
        $this->data['page'] = "author/course/detail";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $this->data['course'] = $course_model->get_course_overview($course_id, $this->user_id);
        $this->data['title'] = $this->data['course']['course_name'];
        $this->data['course']['can_active'] = $course_model->can_active(View::get_data_share('user_id'), $course_id);
        $this->data['course']['course_last_update'] = $course_update_model->get_course_last_update_time($course_id);
        $this->data['course']['course_updates'] = $course_update_model->get_update_course($course_id);
        $this->data['course']['course_lessons'] = $lesson_model->get_lessons($course_id);
        $this->data['course']['course_sections'] = [];
        if (!empty($this->data['course']['course_lessons'])) {
            foreach ($this->data['course']['course_lessons'] as $key => $value) {
                $this->data['course']['course_sections'][$value['lesson_id']] = $section_model->get_sections($value['lesson_id']) != null ? count($section_model->get_sections($value['lesson_id'])) : 0;
            }
        }
        $this->data['response'] = Session::flash('response') ?? null;
        return View::render('layouts/author_layout', $this->data);
    }

    public function active(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        $check = $course_model->update_status(View::get_data_share('user_id'), $course_id, "available");
        Session::flash('response', [
            'status' => $check,
            'message' => $check ? "Active course successfully! Course is available now!" : "Fail to active course! Please try again!"
        ]);
        redirect(route_url('author.course.detail', ['course_id' => $course_id]));
        exit;
    }

    public function deactive(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        $check = $course_model->update_status(View::get_data_share('user_id'), $course_id, "Not available");
        Session::flash('response', [
            'status' => $check,
            'message' => $check ? "Deactive course successfully! Course is not available now!" : "Fail to deactive course! Please try again!"
        ]);
        redirect(route_url('author.course.detail', ['course_id' => $course_id]));
        exit;
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
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SubjectModel
         */
        $subject_model = $this->get_model('author\SubjectModel');
        $this->data['subjects'] = $subject_model->get_subjects();
        $course = $course_model->get_course_overview($course_id, $this->user_id);
        $this->data['title'] = "Edit Course " . $course['course_name'];
        $this->data['page'] = "author/course/edit";
        $this->data['head'] = 'author/_head/create';
        $this->data['script'] = 'author/_script/index';
        $this->data['course'] = $course;
        $this->data['course']['course_id'] = $course_id;
        $this->data['course']['course_subject_id'] = $course['subject_id'];
        $errors = Session::flash('errors');
        $this->data['errors'] = $errors;
        $this->data['response'] = Session::flash('response');
        $this->data['current'] = Session::flash('current');
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
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

        $this->request->validate->field('course_subject_id')
            ->required("Course subject cannot be empty!")
            ->exists('subject', 'subject_id', "Invalid course subject!");

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
            Session::flash('response', [
                'status' => false,
                'message' => 'Validation fail! Please try again!',
            ]);
            Session::flash('errors', $this->request->validate->get_first_error());
            Session::flash('current', $this->request->validate->get_fields_data());
            $this->response->redirect(route_url('author.course.edit', ["course_id" => $course_id]));
            exit;
        } else {
            /**
             * @var CourseUpdateModel
             */
            $course_update_model = $this->get_model('author\CourseUpdateModel');
            $check = true;
            $validated_data = $this->request->validate->get_fields_data();
            $course_name = $validated_data['course_name'];
            $course_description = $validated_data['course_description'];
            $course_thumbnail = $validated_data['course_thumbnail'];
            $course_subject_id = $validated_data['course_subject_id'];
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
            $check = $course_model->update(
                $this->user_id,
                $course_id,
                $course_name,
                $course_subject_id,
                html_entity_decode($course_description),
                $course_thumbnail,
                $course_banner,
                $course_price,
            );
            if ($check) {
                $check = $course_update_model->insert($course_id, html_entity_decode($course_update_description));
            }
            if ($check) {
                Session::flash('response', [
                    'status' => true,
                    'message' => 'Edit course succesfully!',
                ]);
                redirect(route_url("author.course.detail", ["course_id" => $course_id]));
                exit;
            } else {
                Session::flash('response', [
                    'status' => false,
                    'message' => "Errors when edit course. Please try again!",
                ]);
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
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        $check = $course_model->delete($this->user_id, $course_id);
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

    public function add_lesson(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        $this->data['title'] = "Add New Lesson";
        $this->data['page'] = "author/course/new_lesson";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $this->data['response'] = Session::flash('response') ?? null;
        $this->data['errors'] = $this->data['response']['errors'] ?? null;
        $this->data['current'] = Session::flash('current');
        $this->data['something'] = "something";
        $this->data['course'] = $course_model->get_course_overview($course_id, $this->user_id);
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        return View::render('layouts/author_layout', $this->data);
    }

    public function adding_lesson(string $course_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }

        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);

        $this->request->validate->field('lesson_name')
            ->required("Lesson name cannot be empty!");

        if ($this->request->validate->is_error()) {
            Session::flash("response", ['status' => false, 'message' => 'Validation fail! Please try again', 'errors' => $this->request->validate->get_first_error()]);
            Session::flash("current", $this->request->validate->get_fields_data());
            redirect(route_url('author.course.new_lesson', ["course_id" => $course_id]));
            exit;
        } else {
            /**
             * @var LessonModel
             */
            $lesson_model = $this->get_model('author\LessonModel');
            $validated_data = $this->request->validate->get_fields_data();
            $lesson_name = $validated_data['lesson_name'];
            $lesson_description = $validated_data['lesson_description'];
            $check = $lesson_model->insert(
                $course_id,
                $lesson_name,
                html_entity_decode($lesson_description)
            );
            if (!$check) {
                Session::flash('response', [
                    'status' => false,
                    'message' => 'Fail to add lesson!'
                ]);
            } else {
                Session::flash('response', [
                    'status' => true,
                    'message' => 'Add lesson successfully!'
                ]);
            }
            redirect(route_url('author.course.detail', ["course_id" => $course_id]));
            exit;
        }
    }

    public function lesson_detail(string $course_id, string $lesson_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        $this->data['course'] = [
            'course_id' => $course_id,
            'course_name' => $course_model->get_course_name($course_id, $this->user_id),
        ];
        $this->data['lesson'] = $lesson_model->get_lesson($course_id, $lesson_id);
        $this->data['sections'] = $section_model->get_sections($lesson_id);
        $this->data['title'] = $course_model->get_course_name($course_id, $this->user_id) . ' - ' . $this->data['lesson']['lesson_title'];
        $this->data['page'] = "layouts/sub_layouts/lesson_detail_layout";
        $this->data['sub_layouts'] = "author/course/lesson/description";
        $this->data['sub_nav_block'] = "blocks/author/lesson/description";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $this->data['response'] = Session::flash('response') ?? null;
        $this->data['current'] = Session::flash('current');
        return View::render('layouts/author_layout', $this->data);
    }

    public function edit_description(string $course_id, string $lesson_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        $this->data['course'] = [
            'course_id' => $course_id,
            'course_name' => $course_model->get_course_name($course_id, $this->user_id),
        ];
        $this->data['lesson'] = $lesson_model->get_lesson($course_id, $lesson_id);
        $this->data['sections'] = $section_model->get_sections($lesson_id);
        $this->data['title'] = $course_model->get_course_name($course_id, $this->user_id) . ' - ' . $this->data['lesson']['lesson_title'] . ' - Edit description';
        $this->data['page'] = "layouts/sub_layouts/lesson_detail_layout";
        $this->data['sub_layouts'] = "author/course/lesson/edit_description";
        $this->data['sub_nav_block'] = "blocks/author/lesson/edit_description";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $errors = Session::flash('errors');
        $error = Session::flash('error') ?? null;
        $this->data['errors'] = $errors;
        $this->data['error'] = $error;
        $this->data['current'] = Session::flash('current');
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        return View::render('layouts/author_layout', $this->data);
    }

    public function editing_description(string $course_id, string $lesson_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        $data = $this->request->get_fields_data();
        $status = $lesson_model->update($lesson_id, $lesson_model->get_lesson_title($course_id, $lesson_id), html_entity_decode($data['lesson_description']));
        Session::flash('response', ['status' => $status, 'message' => $status ? 'Edit lesson description successfully!' : 'Fail to edit lesson description!']);
        redirect(route_url('author.course.lesson-detail', ['course_id' => $course_id, 'lesson_id' => $lesson_id]));
        exit;
    }

    public function deleting_lesson(string $course_id, string $lesson_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        $status = $lesson_model->delete($lesson_id);
        Session::flash('response', ['status' => $status, 'message' => $status ? 'Delete lesson description successfully!' : 'Fail to delelte lesson description!']);
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        redirect(route_url('author.course.detail', ['course_id' => $course_id]));
        exit;
    }
    public function adding_section(string $course_id, string $lesson_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        $check = $section_model->insert($lesson_id);
        Session::flash('response', [
            'status' => $check,
            'message' => $check ? "Add new section successfully!" : "Fail to add new section!"
        ]);
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        redirect(route_url('author.course.edit-section', ['course_id' => $course_id, "lesson_id" => $lesson_id, "section_id" => $section_model->get_last_section_id($lesson_id)]));
        exit;
    }
    public function view_section(string $course_id, string $lesson_id, string $section_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        if (!($section_model->is_belong_to_lesson($lesson_id, $section_id))) {
            return response([], 403, "Premission Denied");
        }
        $this->data['course'] = [
            'course_id' => $course_id,
            'course_name' => $course_model->get_course_name($course_id, $this->user_id),
        ];
        $this->data['lesson'] = $lesson_model->get_lesson($course_id, $lesson_id);
        $this->data['sections'] = $section_model->get_sections($lesson_id);
        $this->data['title'] = $course_model->get_course_name($course_id, $this->user_id) . ' - ' . $this->data['lesson']['lesson_title'] . ' - ' . $this->data['sections'][$section_id]['section_name'];
        $this->data['section'] = $this->data['sections'][$section_id];
        $this->data['page'] = "layouts/sub_layouts/lesson_detail_layout";
        $this->data['sub_layouts'] = "author/course/lesson/view_section";
        $this->data['sub_nav_block'] = "blocks/author/lesson/view_section";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $errors = Session::flash('errors');
        $error = Session::flash('error') ?? null;
        $this->data['errors'] = $errors;
        $this->data['error'] = $error;
        $this->data['current'] = Session::flash('current');
        $this->data['response'] = Session::flash('response') ?? null;
        return View::render('layouts/author_layout', $this->data);
    }

    public function edit_section(string $course_id, string $lesson_id, string $section_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        if (!($section_model->is_belong_to_lesson($lesson_id, $section_id))) {
            return response([], 403, "Premission Denied");
        }
        $this->data['course'] = [
            'course_id' => $course_id,
            'course_name' => $course_model->get_course_name($course_id, $this->user_id),
        ];
        $this->data['lesson'] = $lesson_model->get_lesson($course_id, $lesson_id);
        $this->data['sections'] = $section_model->get_sections($lesson_id);
        $this->data['section'] = $this->data['sections'][$section_id];
        $this->data['title'] = $course_model->get_course_name($course_id, $this->user_id) . ' - ' . $this->data['lesson']['lesson_title'] . ' - ' . $this->data['sections'][$section_id]['section_name'] . ' - Edit';
        $this->data['page'] = "layouts/sub_layouts/lesson_detail_layout";
        $this->data['sub_layouts'] = "author/course/lesson/edit_section";
        $this->data['sub_nav_block'] = "blocks/author/lesson/edit_section";
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        $errors = Session::flash('errors');
        $error = Session::flash('error') ?? null;
        $this->data['errors'] = $errors;
        $this->data['error'] = $error;
        $this->data['current'] = Session::flash('current');
        $this->data['response'] = Session::flash('response') ?? null;
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        return View::render('layouts/author_layout', $this->data);
    }

    public function editing_section(string $course_id, string $lesson_id, string $section_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var LessonModel
         */
        $lesson_model = $this->get_model('author\LessonModel');
        if (!($lesson_model->is_belong_to_course($course_id, $lesson_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        if (!($section_model->is_belong_to_lesson($lesson_id, $section_id))) {
            return response([], 403, "Premission Denied");
        }

        $this->request->validate->set_fields_data($this->request->get_fields_data());

        $this->request->validate->field("section_name")
            ->required("Section name cannot be empty!");

        if ($this->request->validate->is_error()) {
            Session::flash('response', [
                'status' => false,
                'message' => "Validation error! Please try again!",
                'errors' => $this->request->validate->get_first_error()
            ]);
            redirect(route_url('author.course.edit-section', [
                'course_id' => $course_id,
                'lesson_id' => $lesson_id,
                'section_id' => $section_id
            ]));
            exit;
        } else {
            $validated_data = $this->request->validate->get_fields_data();
            $section_name = $validated_data['section_name'] ?? "Untitled";
            $section_content = $validated_data['section_content'] ?? "Untitled";
            $check = $section_model->update($section_id, $section_name, html_entity_decode($section_content));
            Session::flash('response', [
                'status' => $check,
                'message' => $check ? "Edit section successfully!" : "Fail to edit section. Please try again!",
            ]);
            redirect(route_url('author.course.view-section', [
                'course_id' => $course_id,
                'lesson_id' => $lesson_id,
                'section_id' => $section_id
            ]));
            exit;
        }
    }

    public function deleting_section(string $course_id, string $lesson_id, string $section_id)
    {
        /**
         * @var AuthorCourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        if (View::get_data_share('user_role') != "author" || !($course_model->is_author($course_id, $this->user_id))) {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var SectionModel
         */
        $section_model = $this->get_model('author\SectionModel');
        $check = $section_model->delete($section_id);
        Session::flash('response', [
            'status' => $check,
            'message' => $check ? "Delete section successfully!" : "Fail to delete section!"
        ]);
        $course_model->update_status(View::get_data_share('user_id'), $course_id, 'draft');
        redirect(route_url('author.course.lesson-detail', ['course_id' => $course_id, "lesson_id" => $lesson_id]));
        exit;
    }
}
