<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

use function app\core\helper\load_model;

class PaymentModel extends BaseModel
{
    public function add_payment(int $course_id, int $user_id)
    {
        // Validate
        if (empty($this->db->table('course')->select_field('course_id')->where('course_id', '=', $course_id)->first()))
            return false;
        if (empty($this->db->table('users')->select_field('user_id')->where('user_id', '=', $user_id)->first()))
            return false;
        return $this->db->table('payment')->insert_value([
            "payment_course_id" => $course_id,
            "payment_user_id" => $user_id,
        ]);
    }

    public function get_users_id(int $course_id)
    {
        $user_id = $this->db->table('payment')
            ->select_field('payment_user_id')
            ->where('payment_course_id', '=', $course_id)
            ->get();

        return $user_id;
    }

    public function get_courses_registered(int $user_id)
    {
        $courses = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('payment_user_id', '=', $user_id)
            ->order_by('payment_created_time', '=', true)
            ->get();

        /**
         * @var AuthorModel
         */
        $author_model = load_model('user\AuthorModel');
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');

        if (!empty($courses)) {
            $r_courses = [];
            foreach ($courses as $course) {
                $r_courses[] = [
                    'course_id' => $course['course_id'],
                    'course_name' => $course['course_name'],
                    'course_author' => $author_model->get_author_name($course['course_id']),
                    'course_author_avt' => $author_model->get_author_avatar($course['course_id']),
                    'course_thumbnail' => $course['course_thumbnail'],
                    'course_subject' => $course_model->get_course_subject($course['course_id'])
                ];
            }
            return $r_courses;
        }
        return null;
    }

    public function is_user_learner(int $user_id)
    {
        $courses = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('payment_user_id', '=', $user_id)
            ->order_by('payment_created_time', '=', true)
            ->get();
        if (empty($courses))
            return false;
        return true;
    }
}
