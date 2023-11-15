<?php
$command = [
    "create" => [
        "controller",
        "view",
        "model",
        "middleware"
    ],
    "delete" => [
        "controller",
        "model"
    ]
];
// if (!empty($_SERVER["argv"][1])) {
//     switch ($_SERVER["argv"][1]) {
//         case 'create:controller':
//             if (!empty($_SERVER["argv"][2])) {

//                 $controller_name = $_SERVER["argv"][2];

//                 // Applying filter here

//                 if (!file_exists("app/controllers/$controller_name.php")) {
//                     $output_data = '<?php
// class ' . $controller_name . ' extends  BaseController {
//     public $data = [];

//     public function __construct()
//     {
//         // Constructor ở đây
//     }

//     public function index()
//     {
//         // Trang index của controller;
//     }
// }';
//                     try {
//                         file_put_contents("app/controllers/" . $controller_name . ".php", $output_data);
//                         echo "Đã tạo controller $controller_name thành công!";
//                     } catch (Throwable $tr) {
//                         echo "Có lỗi trong quá trình tạo controller!";
//                     }
//                 } else {
//                     echo "Controller có tên $controller_name đã tồn tại! Vui lòng lựa chọn một tên khác";
//                 }
//             }

//             break;

//         case "delete:controller":
//             $controller_name = $_SERVER["argv"][2];
//             if (file_exists("app/controllers/$controller_name.php")) {
//                 try {
//                     unlink("app/controllers/$controller_name.php");
//                     echo "Xóa controller $controller_name thành công!";
//                 } catch (Throwable $th) {
//                     echo "Có lỗi trong quá trình xóa controller!";
//                 }
//             } else {
//                 echo "Controller $controller_name không tồn tại! Vui lòng kiểm tra lại!";
//             }
//             break;
//         default:
//             echo "Lệnh không tồn tại. Nhập 'help' để xem các lệnh thỏa mãn!";
//             break;
//     }
// }
function main()
{
    if (empty($_SERVER["argv"][1])) {
    }
}

// main();
