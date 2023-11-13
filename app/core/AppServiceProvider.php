<?php

namespace app\core\service;

use app\core\model\user\UserModel;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\load_model;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // $data = $this->db->table("users")->where("id", "=", "1")->first();
        // View::share($data);
        $data = $this->db->table('subject')->select_field('subject_name')->get();
        View::share("subject", $data);
        $user = Session::get('user');

        if (!empty($user)) {
            /**
             * @var UserModel
             */

            $user_model = load_model('user\UserModel');
            $user_id_list = $this->db->table('users')->select_field('user_id')->get();
            foreach ($user_id_list as $key => $value) {
                if ($user == hash('sha256', $value['user_id'])) {
                    View::share('user_name', $user_model->get_user_name($value['user_id']));
                    View::share('user_avt', $user_model->get_user_avatar($value['user_id']));
                    View::share('user_status', $user_model->get_user_status($value['user_id']));
                    break;
                }
            }
        }
    }
}
