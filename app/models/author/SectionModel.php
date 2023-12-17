<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class SectionModel extends BaseModel
{
    public function get_sections(int $lesson_id)
    {
        return $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->get();
    }

    public function get_section(int $lesson_id, int $section_id)
    {
        return $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->where('section.section_id', '=', $section_id)
            ->first();
    }

    public function get_first_section_id(int $lesson_id)
    {
        $id = $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->order_by('section_id', true)
            ->first();
        if (!empty($id)) {
            return $id['lesson_id'];
        }
        return null;
    }
}
