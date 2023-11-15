<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class CourseLessonSectionModel extends BaseModel
{
    public function get(int $course_id)
    {
        return $this->db->table('lesson')
            ->join('section', 'section.lesson_id = lesson.lesson_id')
            ->where('lesson.lesson_course_id', '=', $course_id)
            ->get();
    }
}
