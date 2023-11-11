<?php

namespace app\core\controller;

use app\core\db\DB;
use app\core\http_context\Request;
use app\core\http_context\Response;
use app\core\Template;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\load_view;
use function app\core\helper\view_path;

/**
 * Lớp nền của các lớp controller.
 * @property Database $db Đối tượng database, cho phép thực hiện các tác vụ liên quan đến truy vấn từ CSDL.
 * @property Request $request Đối tượng request, cho phép thực hiện các tác vụ liên quan đến xử lý request từ client.
 * @property Response $response Đối tượng response, cho phép thực hiện các tác vụ liên quan đến xử lý response từ server.
 */
class BaseController
{
    public $db;
    public $request;
    public $response;

    function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->db = (new DB())->get_db();
    }
    /**
     * Trả về đối tượng model cụ thể
     * @param string $model_name Tên model cần lấy
     * @return object Trả về đối tượng model được khởi tạo có tên tương ứng.
     */
    public function get_model($model_name)
    {
        return load_model($model_name);
    }
    /**
     * Xuất view tương ứng với tên và dữ liệu được truyền vào. Lưu ý: Nên sử dụng return `View::render()` để thay thế hàm này.
     * @param string $view_name Tên view cần xuất
     * @param array $data Dữ liệu truyền vào view
     * @return null
     * @deprecated
     */
    public function render_view($view_name, $data = [])
    {
        return View::render($view_name, $data);
    }
}
