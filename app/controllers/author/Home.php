<?php

namespace app\core\controller\author;

use app\core\controller\BaseController;
use app\core\http_context\Response;
use app\core\model\author\CourseModel;
use app\core\model\author\PaymentModel;
use app\core\Session;
use app\core\view\View;
use DateTime;

use function app\core\helper\response;

class Home extends BaseController
{
    private $data = [];
    private $user_id = null;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = View::get_data_share('user_id');
    }
    public function index()
    {
        $this->data["title"] = "Home";
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        /**
         * @var CourseModel
         */
        $course_model = $this->get_model('author\CourseModel');
        /**
         * @var PaymentModel
         */
        $payment_model = $this->get_model('author\PaymentModel');
        $this->data['total_income'] = $payment_model->get_total_payment($this->user_id);
        $datetime = new DateTime('today');
        $today = $datetime->format('Y-m-d H:i:s');
        $datetime = new DateTime('tomorrow');
        $tomorrow = $datetime->format('Y-m-d H:i:s');
        $this->data['today_income'] = $payment_model->get_payment_between($this->user_id, $today, $tomorrow);
        $this->data['total_learners'] = $payment_model->get_total_learners($this->user_id);
        $this->data['today_learners'] = $payment_model->get_total_learners_between($this->user_id, $today, $tomorrow);
        $this->data['total_courses'] = $course_model->get_total_courses($this->user_id);
        $this->data['enrolled_courses'] = 1;
        $this->data['completed_courses'] = 0;
        $this->data['page'] = 'author/home/index';
        $this->data['head'] = 'author/_head/dashboard';
        $this->data['script'] = 'author/_script/dashboard';
        return View::render('layouts/author_layout', $this->data);
    }
}
