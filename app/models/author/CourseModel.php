<?php

namespace app\core\model\author;

use app\core\model\BaseModel;
use app\core\view\View;

class CourseModel extends BaseModel
{
    private $data = [];
    public function __construct()
    {
        parent::__construct();
        $this->data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->get();
    }
    public function get_courses()
    {
        return $this->data;
    }

    public function get_courses_overview()
    {
        $data = $this->db->table('course')
            ->join('subject_course', 'course.course_id = subject_course.course_id')
            ->join('subject', 'subject.subject_id = subject_course.subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->get();
        return $data;
    }
    public function get_available_courses_overview()
    {
        $data = $this->db->table('course')
            ->join('subject_course', 'course.course_id = subject_course.course_id')
            ->join('subject', 'subject.subject_id = subject_course.subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_status', '=', 'available')
            ->get();
        return $data;
    }
    public function get_draft_courses_overview()
    {
        $data = $this->db->table('course')
            ->join('subject_course', 'course.course_id = subject_course.course_id')
            ->join('subject', 'subject.subject_id = subject_course.subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_status', '=', 'draft')
            ->get();
        return $data;
    }

    public function get_course_overview(string $course_id)
    {
        $data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_id', '=', $course_id)
            ->first();
        return $data;
    }

    public function get_course_subject(string $course_id)
    {
        $data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_id', '=', $course_id)
            ->first();
        return $data;
    }

    public function get_course_section(string $course_id)
    {
        $data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_id', '=', $course_id)
            ->first();
        return $data;
    }

    public function is_author(string $course_id)
    {
        $data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', View::get_data_share('user_id'))
            ->where('course_id', '=', $course_id)
            ->first();
        if (empty($data))
            return false;
        return true;
    }

    public function get_last_course_id()
    {
        $id = $this->db->table('course')->select_field('course_id')->order_by('course_id', true)->first();
        if (empty($id)) return null;
        return $id['course_id'];
    }

    public function insert(string $course_name, string $course_description = null, string $course_thumbnail = null, string $course_banner = null, int $course_price = 0)
    {
        $last_course = $this->db->table('course')
            ->select_field('course_id')->order_by('course_id', true)->first();

        $course_id = intval($last_course["course_id"]) + 1;

        $a = $this->db->table('course')->insert_value([
            "course_name" => $course_name,
            "course_description" => $course_description,
            "course_status" => "draft",
            "course_thumbnail" => $course_thumbnail,
            "course_banner" => $course_banner,
            "course_price" => $course_price,
            "course_author_id" => View::get_data_share('user_id')
        ]);
        $b = $this->db->table('course_update')->insert_value([
            "course_id" => $course_id,
            "course_update_description" => "<p>Course Created!</p>"
        ]);

        return $a && $b;
    }

    public function update(string $course_id, string $course_name, string $course_description = null, string $course_thumbnail = null, string $course_banner = null, int $course_price = 0)
    {
        $check = $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->update_value([
                "course_name" => $course_name,
                "course_description" => $course_description,
                "course_price" => $course_price,
            ]);
        if ($course_thumbnail != null) {
            $check = $check && $this->db->table('course')
                ->where('course_id', '=', $course_id)
                ->update_value([
                    "course_thumbnail" => $course_thumbnail,
                ]);
        }
        if ($course_banner != null) {
            $check = $check && $this->db->table('course')
                ->where('course_id', '=', $course_id)
                ->update_value([
                    "course_banner" => $course_banner,
                ]);
        }
        return $check;
    }

    public function update_status(string $course_id, string $course_status = "draft")
    {
        if ($course_status != "draft" && $course_status != "available" && $course_status != "Not available")
            return false;
        return $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->update_value([
                "course_status" => $course_status,
            ]);
    }

    public function delete(string $course_id)
    {
        return $this->db->table('course')->where('course_id', '=', $course_id)->delete_value();
    }
}
