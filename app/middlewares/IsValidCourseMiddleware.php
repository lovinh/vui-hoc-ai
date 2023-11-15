<?php

namespace app\core\middleware\user;

use app\core\http_context\Request;
use app\core\middleware\IMiddleware;
use app\core\model\user\CourseModel;
use app\core\Route;
use app\core\view\View;

use function app\core\helper\load_model;
use function app\core\helper\redirect;

class IsValidCourseMiddleware implements IMiddleware
{
    public function handle(Request $request, callable $next, $params = [])
    {
        /**
         * @var CourseModel
         */
        $course_model = load_model('user\CourseModel');
        if (!$course_model->is_course_exists($params[0])) {
            redirect($params[0].'/not-found', 404);
            exit;
        }
        return $next($request);
    }
}
