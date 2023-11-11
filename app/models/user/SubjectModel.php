<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class SubjectModel extends BaseModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function load_subject()
    {
        $subjects = $this->db->table('subject')->get();

        return $subjects;
    }

    public function load_subject_name()
    {
        $subjects_name = $this->db->table('subject')->select_field('subject_name')->get();

        return $subjects_name;
    }
}
