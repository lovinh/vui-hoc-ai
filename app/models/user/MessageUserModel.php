<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class MessageUserModel extends BaseModel
{
    public function add_message_user(int $message_id, int $user_id)
    {
        return $this->db->table('user_message')
        ->insert_value([
            "message_id" => $message_id,
            "user_id" => $user_id
        ]);
    }
}
