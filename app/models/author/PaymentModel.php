<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

use function app\core\helper\str_date;

class PaymentModel extends BaseModel
{
    public function get_total_payment(string $author_id)
    {
        $data = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('course.course_author_id', '=', $author_id)
            ->select_field('SUM(course.course_price)')
            ->first();
        return intval($data["SUM(course.course_price)"]) ?? null;
    }
    public function get_payment_between(string $author_id, string $start_date, string $end_date)
    {
        $data = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('course.course_author_id', '=', $author_id)
            ->where_between('payment_created_time', str_date($start_date, "Y-m-d"), str_date($end_date, "Y-m-d"))
            ->select_field('SUM(course.course_price)')
            ->first();
        return intval($data["SUM(course.course_price)"]) ?? null;
    }

    public function get_total_learners(string $author_id)
    {
        $data = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('course.course_author_id', '=', $author_id)
            ->select_field('COUNT(DISTINCT payment.payment_user_id)')
            ->first();
        return intval($data["COUNT(DISTINCT payment.payment_user_id)"]) ?? null;
    }

    public function get_total_learners_between(string $author_id, string $start_date, string $end_date)
    {
        $data = $this->db->table('payment')
            ->join('course', 'course.course_id = payment.payment_course_id')
            ->where('course.course_author_id', '=', $author_id)
            ->where_between('payment_created_time', str_date($start_date, "Y-m-d"), str_date($end_date, "Y-m-d"))
            ->select_field('COUNT(DISTINCT payment.payment_user_id)')
            ->first();
        return intval($data["COUNT(DISTINCT payment.payment_user_id)"]) ?? null;
    }
}
