<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class LessonModel extends BaseModel
{
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
}
