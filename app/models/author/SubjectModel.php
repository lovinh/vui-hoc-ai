<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class SubjectModel extends BaseModel
{
    public function get_subjects()
    {
        return $this->db->table('subject')->get();
    }
    public function get_subject(string $subject_id)
    {
        $subject = $this->db->table('subject')->where('subject_id', '=', $subject_id)->first();
        return $subject;
    }
    public function get_subject_name(string $subject_id)
    {
        $name = $this->db->table('subject')->select_field('subject_name')->where('subject_id', '=', $subject_id)->first();
        if (empty($name)) return null;
        return $name['subject_name'];
    }
    public function get_subject_by_name(string $subject_name)
    {
        $subject = $this->db->table('subject')->select_field()->where('subject_name', '=', $subject_name)->first();
        return $subject;
    }
    public function get_last_subject_id()
    {
        $id = $this->db->table('subject')->select_field('subject_id')->order_by('subject_id', true)->first();
        if (empty($id)) return null;
        return $id['subject_id'];
    }
    public function insert(string $subject_name, string $subject_description = "", string $parent_subject_id = null)
    {
        return $this->db->table('subject')->insert_value([
            "subject_name" => $subject_name,
            "subject_description" => $subject_description,
            "parent_subject_id" => $parent_subject_id
        ]);
    }
}
