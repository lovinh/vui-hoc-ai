<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class LessonModel extends BaseModel
{
    public function is_belong_to_course(string $course_id, string $lesson_id)
    {
        $check = $this->db->table('lesson')
            ->where('lesson_id', '=', $lesson_id)
            ->where('lesson_course_id', '=', $course_id)
            ->first();
        if ($check == null) return false;
        return true;
    }
    
    public function get_lessons(int $course_id)
    {
        return $this->db->table('lesson')
            ->where('lesson.lesson_course_id', '=', $course_id)
            ->get();
    }

    public function get_first_lesson_id(int $course_id)
    {
        $id =  $this->db->table('lesson')
            ->where('lesson.lesson_course_id', '=', $course_id)
            ->order_by('lesson_id', true)
            ->first();
        if (!empty($id)) {
            return $id['lesson_id'];
        }
        return null;
    }

    public function get_lesson_title(string $course_id, string $lesson_id)
    {
        $title = $this->db->table('lesson')
            ->select_field('lesson_title')
            ->where('lesson_course_id', '=', $course_id)
            ->where('lesson_id',  '=', $lesson_id)
            ->first();
        return $title['lesson_title'] ?? null;
    }

    public function get_lesson_description(string $course_id, string $lesson_id)
    {
        $description = $this->db->table('lesson')
            ->select_field('lesson_description')
            ->where('lesson_course_id', '=', $course_id)
            ->where('lesson_id',  '=', $lesson_id)
            ->first();
        return $description['lesson_description'] ?? null;
    }

    public function get_lesson(string $course_id, string $lesson_id)
    {
        $lesson = $this->db->table('lesson')
            ->where('lesson_course_id', '=', $course_id)
            ->where('lesson_id',  '=', $lesson_id)
            ->first();
        return $lesson ?? null;
    }

    public function insert(string $course_id, string $lesson_title, string $lesson_description)
    {
        return $this->db->table('lesson')
            ->insert_value([
                "lesson_course_id" => $course_id,
                "lesson_title" => $lesson_title,
                "lesson_description" => $lesson_description,
            ]);
    }

    public function update(string $lesson_id, string $lesson_title, string $lesson_description)
    {
        return $this->db->table('lesson')
            ->where('lesson_id', '=', $lesson_id)
            ->update_value([
                'lesson_title' => $lesson_title,
                'lesson_description' => $lesson_description
            ]);
    }

    public function delete(string $lesson_id)
    {
        return $this->db->table('lesson')
            ->where('lesson_id', '=', $lesson_id)
            ->delete_value();
    }
}
