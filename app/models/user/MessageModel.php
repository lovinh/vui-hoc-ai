<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class MessageModel extends BaseModel
{

    public function get_last_message_id()
    {
        $id = $this->db->table('message')->select_field('message_id')->order_by('message_id', true)->limit(1)->first();
        if (!empty($id)) {
            return $id["message_id"];
        }
        return null;
    }

    public function add_message(string $type, string $content, int $user_id = null)
    {
        return $this->db->table('message')
            ->insert_value([
                "message_type" => $type,
                "message_content" => $content,
                "message_user_id" => $user_id,
            ]);
    }
}
