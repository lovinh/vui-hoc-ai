<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class NoteModel extends BaseModel
{
    public function get_notes(int $user_id, int $course_id)
    {
        return $this->db->table('note')
            ->where('note_user_id', '=', $user_id)
            ->where('note_course_id', '=', $course_id)
            ->get();
    }

    public function get_note(int $user_id, int $course_id, int $note_id)
    {
        return $this->db->table('note')
            ->where('note_user_id', '=', $user_id)
            ->where('note_course_id', '=', $course_id)
            ->where('note_id', '=', $note_id)
            ->first();
    }

    public function add_note(int $user_id, int $course_id, string $note_content)
    {
        return $this->db->table('note')
            ->insert_value([
                "note_course_id" => $course_id,
                "note_user_id" =>  $user_id,
                "note_content" => $note_content,
            ]);
    }

    public function delete_note(int $user_id, int $course_id, int $note_id)
    {
        return $this->db->table('note')
            ->where('note_user_id', '=', $user_id)
            ->where('note_course_id', '=', $course_id)
            ->where('note_id', '=', $note_id)
            ->delete_value();
    }

    public function edit_note(int $user_id, int $course_id, int $note_id, string $note_content)
    {
        return $this->db->table('note')
            ->where('note_user_id', '=', $user_id)
            ->where('note_course_id', '=', $course_id)
            ->where('note_id', '=', $note_id)
            ->update_value([
                "note_content" => $note_content
            ]);
    }
}
