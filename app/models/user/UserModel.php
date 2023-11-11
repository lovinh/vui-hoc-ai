<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class UserModel extends BaseModel
{
    public function get_users(int $limit = -1)
    {
        if ($limit == -1)
            $user = $this->db->table('users')->select_field()->where('user_status', '=', 'active')->get();
        else
            $user = $this->db->table('users')->select_field()->where('user_status', '=', 'active')->limit($limit)->get();

        return $user;
    }

    public function get_user_name(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_first_name, user_last_name')->where('user_status', '=', 'active')->where('user_id', '=', $user_id)->first();
        if (!empty($user)) {
            return $user['user_first_name'] . " " . $user['user_last_name'];
        }
        return null;
    }

    public function get_user_avatar(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_avatar_link')->where('user_status', '=', 'active')->where('user_id', '=', $user_id)->first();

        if (!empty($user)) {
            return $user['user_avatar_link'];
        }
        return null;
    }
}
