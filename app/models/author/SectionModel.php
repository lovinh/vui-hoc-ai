<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class SectionModel extends BaseModel
{
    public function is_belong_to_lesson(string $lesson_id, string $section_id)
    {
        $check = $this->db->table('section')
            ->where('lesson_id', '=', $lesson_id)
            ->where('section_id', '=', $section_id)
            ->first();
        if ($check == null) return false;
        return true;
    }

    public function get_sections(string $lesson_id)
    {
        $sections =  $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->get();

        if ($sections == null) return null;
        $res = [];
        foreach ($sections as $key => $section) {
            $res[$section['section_id']] = $section;
        }
        return $res;
    }

    public function get_section(string $lesson_id, string $section_id)
    {
        return $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->where('section.section_id', '=', $section_id)
            ->first();
    }

    public function get_section_name(string $lesson_id, string $section_id)
    {
        $section = $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->where('section.section_id', '=', $section_id)
            ->first();
        return $section['section_name'] ?? null;
    }

    public function get_section_content(string $lesson_id, string $section_id)
    {
        $section = $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->where('section.section_id', '=', $section_id)
            ->first();
        return $section['section_content'] ?? null;
    }

    public function get_last_section_id(string $lesson_id)
    {
        $id = $this->db->table('section')
            ->where('section.lesson_id', '=', $lesson_id)
            ->order_by('section_id', true)
            ->first();
        if (!empty($id)) {
            return $id['section_id'];
        }
        return null;
    }

    public function insert(string $lesson_id, string $section_name = "Untitled", string $section_content = null)
    {
        return $this->db->table('section')->insert_value(
            [
                "lesson_id" => $lesson_id,
                "section_name" => $section_name,
                "section_content" => $section_content,
            ]
        );
    }

    public function update(string $section_id, string $section_name, string $section_content)
    {
        return $this->db->table("section")
            ->where('section_id', '=', $section_id)
            ->update_value([
                "section_name" => $section_name,
                "section_content" => $section_content,
            ]);
    }

    public function delete(string $section_id)
    {
        return $this->db->table("section")
            ->where('section_id', '=', $section_id)
            ->delete_value();
    }
}
