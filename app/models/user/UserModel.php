<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class UserModel extends BaseModel
{
    public function get_users(int $limit = -1)
    {
        if ($limit == -1)
            $user = $this->db->table('users')->select_field()->get();
        else
            $user = $this->db->table('users')->select_field()->limit($limit)->get();

        return $user;
    }

    public function get_user_name(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_first_name, user_last_name')->where('user_id', '=', $user_id)->first();
        if (!empty($user)) {
            return $user['user_first_name'] . " " . $user['user_last_name'];
        }
        return null;
    }

    public function get_user_avatar(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_avatar_link')->where('user_id', '=', $user_id)->first();

        if (!empty($user)) {
            return $user['user_avatar_link'];
        }
        return null;
    }

    public function get_last_user_id()
    {
        $id = $this->db->table('users')
            ->select_field('user_id')
            ->order_by('user_id', true)
            ->limit(1)
            ->first();

        if (!empty($id)) {
            return $id['user_id'];
        }
        return null;
    }

    public function get_user_status(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_status')->where('user_id', '=', $user_id)->first();

        if (!empty($user)) {
            return $user['user_status'];
        }
        return null;
    }

    public function get_user_role(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_role')->where('user_id', '=', $user_id)->first();

        if (!empty($user)) {
            return $user['user_role'];
        }
        return null;
    }

    public function get_user_email(int $user_id)
    {
        $user = $this->db->table('users')->select_field('user_email')->where('user_id', '=', $user_id)->first();

        if (!empty($user)) {
            return $user['user_email'];
        }
        return null;
    }

    public function update_user(int $user_id, array $data)
    {
        return $this->db->table('users')->where('user_id', '=', $user_id)->update_value($data);
    }
}
