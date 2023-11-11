<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class AuthorModel extends BaseModel
{
    public function get_authors(int $limit = -1)
    {
        if ($limit == -1)
            $author = $this->db->table('users')->select_field()->where('user_role', '=', 'author')->where('user_status', '=', 'active')->get();
        else
            $author = $this->db->table('users')->select_field()->where('user_role', '=', 'author')->where('user_status', '=', 'active')->limit($limit)->get();

        return $author;
    }

    public function get_author_name(int $author_id)
    {
        $author = $this->db->table('users')->select_field('user_first_name, user_last_name')->where('user_role', '=', 'author')->where('user_status', '=', 'active')->where('user_id', '=', $author_id)->first();

        if (!empty($author)) {
            return $author['user_first_name'] . " " . $author['user_last_name'];
        }
        return null;
    }

    public function get_author_avatar(int $author_id)
    {
        $author = $this->db->table('users')->select_field('user_avatar_link')->where('user_role', '=', 'author')->where('user_status', '=', 'active')->where('user_id', '=', $author_id)->first();

        if (!empty($author)) {
            return $author['user_avatar_link'];
        }
        return null;
    }
}
