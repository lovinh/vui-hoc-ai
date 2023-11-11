
<?php

use app\core\controller\user\Auth;
use app\core\controller\user\Course;
use app\core\controller\user\Home;
use app\core\model\user\CourseModel;
use app\core\Route;
use app\core\view\View;

use function app\core\helper\load_model;

Route::get('/', [Home::class, 'index'])->name("user.home.index");

Route::get('/course', [Course::class, 'index'])->name("user.course.index");

Route::get('/course/subject/{subject}', [Course::class, 'search_by_subject'])->name('user.course.search_subject');

Route::get('/course/detail/{id}', [Course::class, 'detail'])
    ->name('user.course.detail')
    ->where('id', '^[0-9]*$');

Route::get('/search', [Course::class, 'search_by_name'])->name('user.course.search_name');

// Authentication
Route::get('/authentication', [Auth::class, 'index'])->name('user.auth.index');

Route::post('/auth/sign_in', [Auth::class, 'sign_in'])->name('user.auth.sign_in');

Route::get('/auth/sign_out', [Auth::class, 'sign_out'])->name('user.auth.sign_out');

Route::fallback(function () {
    $data = [
        'view' => 'errors/404',
        'page-title' => "Vui Hoc AI - Page not found"
    ];
    return View::render('layouts/user_layout', $data);
});

// Just for testing
Route::get('/test', function () {
    return View::render('user/auth');
});
