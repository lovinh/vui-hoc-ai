<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class CourseUpdateModel extends BaseModel
{
    public function get_course_last_update_time(int $course_id)
    {
        $last_update = $this->db->table('course')
            ->join('course_update', 'course_update.course_id = course.course_id')
            ->select_field("course_update_time")
            ->where('course.course_id', '=', $course_id)
            ->order_by('course_update_id', true)
            ->first();

        if (!empty($last_update)) {
            return date("d-m-Y", strtotime($last_update["course_update_time"]));
        }
        return null;
    }
    public function insert(string $course_id, string $course_description)
    {
        return $this->db->table('course_update')->insert_value([
            "course_id" => $course_id,
            "course_update_description" => $course_description
        ]);
    }
    public function get_update_course(string $course_id)
    {
        return $this->db->table('course_update')
            ->where('course_id', '=', $course_id)
            ->order_by('course_update_time', true)
            ->get();
    }
    public function delete(string $course_id)
    {
        return $this->db->table('course_update')
            ->where('course_id', '=', $course_id)
            ->delete_value();
    }
}
