<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\Session;
use app\core\db\Database;
use app\core\db\QueryBuilder;
use app\core\view\View;

use function app\core\helper\redirect;
use function app\core\helper\route_url;

class Auth extends BaseController
{
    public function index()
    {
        $error = Session::flash('error');
        $err = null;
        if (!empty($error)) {
            foreach ($error as $key => $value) {
                $err = $value;
                break;
            }
        }
        $data = [
            'error' => $err,
            'old_email' => Session::flash('old_email')
        ];
        return View::render("user/auth", $data);
    }

    public function sign_in()
    {
        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);
        $this->request->validate->field('sign_in_email')
            ->required("Email is required!");

        $this->request->validate->field('sign_in_password')
            ->required("Password is required!");

        $this->request->validate->field('sign_in_email')
            ->email("Please enter a valid email!")
            ->min_char(6, "Email have to be 6 charactors minimum!")
            ->max_char(255, "Email have to be only 255 charactors maximum!");

        $this->request->validate->field('sign_in_password')
            ->min_char(6, "Password have to be 6 charactors minimum!")
            ->hashed('md5');

        if ($this->request->validate->is_error()) {
            Session::flash('error', $this->request->validate->get_first_error());
            Session::flash('old_email', $this->request->validate->get_field_data('sign_in_email'));
            $this->response->redirect(route_url('user.auth.index'));
            exit;
        } else {
            $email = $this->request->validate->get_field_data('sign_in_email');
            $password = $this->request->validate->get_field_data('sign_in_password');
            $user = $this->db->table('users')
                ->select_field('user_id, user_email')
                ->where('user_email', '=', $email)
                ->where('user_password', '=', $password)
                ->first();
            if (empty($user)) {
                Session::flash('error', ["Invalid email or password!"]);
                Session::flash('old_email', $this->request->validate->get_field_data('sign_in_email'));
                $this->response->redirect(route_url('user.auth.index'));
                exit;
            } else {
                $user_id = $user['user_id'];
                Session::put('user', hash('sha256', $user_id));
                redirect(route_url('user.home.index'));
                exit;
            }
        }
    }

    public function sign_out()
    {
        Session::delete('user');
        redirect(route_url('user.home.index'));
        exit;
    }

    public function sign_up()
    {
        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);
    }
}
