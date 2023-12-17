<?php

namespace app\core\controller\author;

use app\core\controller\BaseController;
use app\core\http_context\Response;
use app\core\Session;
use app\core\view\View;

use function app\core\helper\response;

class Home extends BaseController
{
    private $data = [];
    public function __construct()
    {
    }
    public function index()
    {
        $this->data["title"] = "Home";
        if (View::get_data_share('user_role') != "author") {
            return response([], 403, "Premission Denied");
        }
        $this->data['page'] = 'author/home/index';
        $this->data['head'] = 'author/_head/index';
        $this->data['script'] = 'author/_script/index';
        return View::render('layouts/author_layout', $this->data);
    }
}
