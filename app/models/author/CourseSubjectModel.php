<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class CourseSubjectModel extends BaseModel
{
    public function get_course_subject(string $course_id)
    {
        $subject = $this->db->table('subject_course')
            ->join('course', 'subject_course.course_id = course.course_id')
            ->join('subject', 'subject_course.subject_id = subject.subject_id')
            ->where('subject_course.course_id', '=', $course_id)
            ->select_field('subject.subject_name')
            ->first();
        if (empty($subject))
            return null;
        return $subject['subject_name'];
    }
    public function get_course_subject_id(string $course_id)
    {
        $subject = $this->db->table('subject_course')
            ->where('subject_course.course_id', '=', $course_id)
            ->select_field('subject_course.subject_id')
            ->first();
        if (empty($subject))
            return null;
        return $subject['subject_id'];
    }
    public function insert(string $subject_id, string $course_id)
    {
        return $this->db->table('subject_course')->insert_value([
            "subject_id" => $subject_id,
            "course_id" => $course_id,
        ]);
    }
    public function update(string $subject_id, string $course_id, string $new_subject_id)
    {
        return $this->db->table('subject_course')
            ->where('subject_id', '=', $subject_id)
            ->where('course_id', '=', $course_id)
            ->update_value([
                "subject_id" => $new_subject_id
            ]);
    }
    public function delete(string $subject_id, string $course_id)
    {
        return $this->db->table('subject_course')
            ->where('subject_id', '=', $subject_id)
            ->where('course_id', '=', $course_id)
            ->delete_value();
    }
}
