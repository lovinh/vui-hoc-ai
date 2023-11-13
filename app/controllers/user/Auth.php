<?php

namespace app\core\controller\user;

use app\core\controller\BaseController;
use app\core\Session;
use app\core\db\Database;
use app\core\db\QueryBuilder;
use app\core\helper\CustomClass;
use app\core\helper\GenerateCode;
use app\core\helper\MailSend;
use app\core\model\user\UserModel;
use app\core\view\View;
use Exception;

use function app\core\helper\load_model;
use function app\core\helper\mask_email;
use function app\core\helper\public_path;
use function app\core\helper\redirect;
use function app\core\helper\route_url;

class Auth extends BaseController
{
    public function index()
    {
        if (!empty(Session::get('user'))) {
            redirect(route_url('user.home.index'));
            exit;
        }
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
        return View::render("user/auth/sign_in", $data);
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

    public function register()
    {
        if (!empty(Session::get('user'))) {
            redirect(route_url('user.home.index'));
            exit;
        }
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
            'old_email' => Session::flash('old_email'),
            'old_first_name' => Session::flash('old_first_name'),
            'old_last_name' => Session::flash('old_last_name'),
        ];
        return View::render("user/auth/sign_up", $data);
    }
    public function sign_up()
    {
        $data = $this->request->get_fields_data();
        $this->request->validate->set_fields_data($data);
        $this->request->validate->field('sign_up_first_name')
            ->required("First name is required");
        $this->request->validate->field('sign_up_last_name')
            ->required("Last name is required");
        $this->request->validate->field('sign_up_email')
            ->required("Email is required");
        $this->request->validate->field('sign_up_password')
            ->required("Password is required");
        $this->request->validate->field('sign_up_repassword')
            ->required("Re-password is required");
        $this->request->validate->field('sign_up_first_name')
            ->max_char(256, "First name cannot be more 256 charactors");
        $this->request->validate->field('sign_up_last_name')
            ->max_char(256, "Last name cannot be more 256 charactors");
        $this->request->validate->field('sign_up_email')
            ->email("Please enter a valid email!")
            ->min_char(6, "Email have to be 6 charactors minimum!")
            ->max_char(255, "Email have to be only 255 charactors maximum!")
            ->unique('users', 'user_email', "Email have exist!");
        $this->request->validate->field('sign_up_password')
            ->min_char(6, "Password have to be 6 charactors minimum!")
            ->like("~^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{0,}$~is", "Password have to be at least 1 lowercase English letter, 1 uppercase English letter, 1 digit and 1 special charactor!");
        $this->request->validate->field('sign_up_repassword')
            ->min_char(6, "Re-password have to be 6 charactors minimum!")
            ->match('sign_up_password', "Re-password must be match the password!");

        if ($this->request->validate->is_error()) {
            Session::flash('error', $this->request->validate->get_first_error());
            Session::flash('old_first_name', $this->request->validate->get_field_data('sign_up_first_name'));
            Session::flash('old_last_name', $this->request->validate->get_field_data('sign_up_last_name'));
            Session::flash('old_email', $this->request->validate->get_field_data('sign_up_email'));
            $this->response->redirect(route_url('user.auth.register'));
            exit;
        } else {
            $this->request->validate->field('sign_up_password')
                ->hashed('md5');
            // Lưu
            $first_name = $this->request->validate->get_field_data('sign_up_first_name');
            $last_name = $this->request->validate->get_field_data('sign_up_last_name');
            $email = $this->request->validate->get_field_data('sign_up_email');
            $password = $this->request->validate->get_field_data('sign_up_password');
            try {
                $this->db->table('users')->insert_value([
                    "user_first_name" => $first_name,
                    "user_last_name" => $last_name,
                    "user_email" => $email,
                    "user_password" => $password,
                ]);
                /**
                 * @var UserModel
                 */
                $model = load_model('user\UserModel');
                $user_id = $model->get_last_user_id();
                Session::put('user', hash('sha256', $user_id));
                redirect(route_url('user.auth.validate_email'));
                exit;
            } catch (Exception $ex) {
                Session::flash('error', ["Something went wrong when creating account! Please try again!"]);
                $this->response->redirect(route_url('user.auth.register'));
                exit;
            }
        }
    }

    public function validate_email()
    {
        /**
         * @var UserModel
         */
        $model = load_model('user\UserModel');
        $user = Session::get('user');
        $user_id = null;
        $user_id_list = $this->db->table('users')->select_field('user_id')->get();
        foreach ($user_id_list as $key => $value) {
            if ($user == hash('sha256', $value['user_id'])) {
                $user_id = $value['user_id'];
                break;
            }
        }

        if (empty($user_id)) {
            $this->response->redirect(route_url('user.auth.register'));
            exit;
        }

        // Gửi email
        $code = GenerateCode::generate(6);
        Session::flash("code", $code);
        $content = file_get_contents(public_path('assets/user/files/mail-content.html'));

        if (preg_match("~{{code}}~is", $content)) {
            $content = preg_replace("~{{code}}~is", $code, $content);
        }

        $email = $model->get_user_email($user_id);
        $user_name = $model->get_user_name($user_id);
        $subject = "Validate your account - Vui hoc AI";
        MailSend::send($user_name, $email, $subject, $content);
        Session::flash("user_id", $user_id);

        // Hiển thị khung để người dùng nhập
        $email = mask_email($email);
        $data = [
            'page-title' => "Validate Email Address - Vui Hoc AI",
            'email' => $email,
            'error' => Session::flash('error'),
        ];
        return View::render('user/auth/validate_email', $data);
    }
    public function validating_email_send_again()
    {
        Session::flash('error', "We have sent you an email again!");
        redirect(route_url('user.auth.validate_email'));
        exit;
    }
    public function validating_email()
    {
        // Kiểm tra code
        if ($this->request->get_fields_data()['validate_email_code'] == Session::flash('code')) {
            // Validate thành công
            /**
             * @var UserModel
             */
            $model = load_model('user\UserModel');
            $user_id = Session::flash('user_id');
            $model->update_user($user_id, [
                "user_status" => "active"
            ]);
            redirect(route_url('user.home.index'));
            exit;
        } else {
            // Validate thất bại
            Session::flash('error', "Invalid Validating Code! We have sent you an email again!");
            redirect(route_url('user.auth.validate_email'));
            exit;
        }
    }

    public function forget_password_index()
    {
        $data = [
            'page-title' => "Recovery password - Confirm email - Vui Hoc AI",
            'error' => Session::flash('error'),
        ];
        return View::render('user/auth/forget_password_index', $data);
    }

    public function forget_password_email_input()
    {

        $this->request->validate->set_fields_data($this->request->get_fields_data());
        $this->request->validate->field('forget_password_email')
            ->required('Email is required')
            ->min_char(6, "Email have to be 6 charactors minimum!")
            ->max_char(255, "Email have to be only 255 charactors maximum!")
            ->exists('users', 'user_email', "Email have not exist!");

        if ($this->request->validate->is_error()) {
            Session::flash('error', $this->request->validate->get_first_error('forget_password_email'));
            redirect(route_url('user.auth.forget_password'));
            exit;
        } else {
            Session::put('email',  $this->request->validate->get_field_data('forget_password_email'));
            redirect(route_url('user.auth.forget_password_email'));
            exit;
        }
    }

    public function forget_password_validate_email()
    {
        $code = GenerateCode::generate(6);
        Session::flash("code", $code);
        $content = file_get_contents(public_path('assets/user/files/recovery-password-mail.html'));
        /**
         * @var string
         */
        $email = Session::flash('email');
        if (empty($email)) {
            redirect(route_url('user.auth.index'));
            exit;
        }
        $user_id = $this->db->table('users')->select_field('user_id, user_last_name')->where('user_email', '=', $email)->limit(1)->first();
        $user_name = $user_id['user_last_name'];
        $user_id = $user_id['user_id'];
        if (preg_match("~{{code}}~is", $content)) {
            $content = preg_replace("~{{code}}~is", $code, $content);
        }
        if (preg_match("~{{name}}~is", $content)) {
            $content = preg_replace("~{{name}}~is", $user_name, $content);
        }
        $subject = "Recovery password - Validate your email address - Vui hoc AI";
        MailSend::send($user_name, $email, $subject, $content);

        Session::flash('email', $email);

        $data = [
            'page-title' => "Recovery password - Validating code - Vui Hoc AI",
            'email' => mask_email($email),
            'error' => Session::flash('error'),
        ];
        return View::render('user/auth/forget_password_email', $data);
    }

    public function forget_password_validating_email()
    {
        // Kiểm tra code
        if ($this->request->get_fields_data()['validate_email_code'] == Session::flash('code')) {
            // Validate thành công
            $email = Session::flash('email');
            $user_id = $this->db->table('users')->select_field('user_id')->where('user_email', '=', $email)->limit(1)->first();
            Session::put('user_id', $user_id['user_id']);
            redirect(route_url('user.auth.forget_password_new'));
            exit;
        } else {
            // Validate thất bại
            Session::flash('error', "Invalid Validating Code! We have sent you an email again!");
            redirect(route_url('user.auth.forget_password_email'));
            exit;
        }
    }

    public function forget_password_new_password()
    {
        if ($this->request->is_get()) {
            $error = Session::flash('error');
            $err = null;
            if (!empty($error)) {
                foreach ($error as $key => $value) {
                    $err = $value;
                    break;
                }
            }
            $user_id = Session::get('user_id');
            if (empty($user_id)) {
                redirect(route_url('user.auth.index'));
                exit;
            }
            $user_name = $this->db->table('users')->select_field('user_last_name')->where('user_id', '=', $user_id)->limit(1)->first();
            $user_name = $user_name['user_last_name'];
            $data = [
                'page-title' => "Recovery password - New password - Vui Hoc AI",
                'error' => $err,
                'user_name' => $user_name,
            ];
            return View::render('user/auth/forget_password_recovery', $data);
        } else {
            $data = $this->request->get_fields_data();
            $this->request->validate->set_fields_data($data);
            $this->request->validate->field('forget_password_new_pass')
                ->required("New password is required");
            $this->request->validate->field('forget_password_new_pass_again')
                ->required("Re-new password is required");
            $this->request->validate->field('forget_password_new_pass')
                ->min_char(6, "New password have to be 6 charactors minimum!")
                ->like("~^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{0,}$~is", "New password have to be at least 1 lowercase English letter, 1 uppercase English letter, 1 digit and 1 special charactor!");
            $this->request->validate->field('forget_password_new_pass_again')
                ->min_char(6, "Re-new password have to be 6 charactors minimum!")
                ->match('forget_password_new_pass', "Re-new password must be match the password!");

            $this->request->validate->field('forget_password_new_pass')
                ->hashed('md5');
            if ($this->request->validate->is_error()) {
                Session::flash('error', $this->request->validate->get_first_error());
                $this->response->redirect(route_url('user.auth.forget_password_new'));
                exit;
            } else {
                // Lưu
                try {
                    $password = $this->request->validate->get_field_data('forget_password_new_pass');
                    /**
                     * @var string
                     */
                    $user_id = Session::get('user_id');
                    Session::put('user', hash('sha256', $user_id));
                    Session::delete('user_id');
                    /**
                     * @var UserModel
                     */
                    $model = load_model('user\UserModel');
                    $model->update_user($user_id, [
                        "user_password" => $password
                    ]);
                    redirect(route_url('user.home.index'));
                    exit;
                } catch (Exception $ex) {
                    Session::flash('error', ["Something went wrong when recover password! Please try again!"]);
                    $this->response->redirect(route_url('user.auth.forget_password_new'));
                    exit;
                }
            }
        }
    }
}
