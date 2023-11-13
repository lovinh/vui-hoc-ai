
<?php

use app\core\controller\user\Auth;
use app\core\controller\user\Course;
use app\core\controller\user\Enrollment;
use app\core\controller\user\Home;
use app\core\middleware\user\AuthBackHomeMiddleware;
use app\core\middleware\user\AuthBackToSignInMiddleware;
use app\core\middleware\user\AuthHasLoginedMiddleware;
use app\core\model\user\CourseModel;
use app\core\Route;
use app\core\view\View;
use PHPMailer\PHPMailer\PHPMailer;

use function app\core\helper\load_model;

Route::get('/', [Home::class, 'index'])->name("user.home.index");

Route::get('/course', [Course::class, 'index'])->name("user.course.index");

Route::get('/course/subject/{subject}', [Course::class, 'search_by_subject'])->name('user.course.search_subject');

Route::get('/course/detail/{id}', [Course::class, 'detail'])
    ->name('user.course.detail')
    ->where('id', '^[0-9]*$');

Route::get('/search', [Course::class, 'search_by_name'])->name('user.course.search_name');

// Authentication
Route::group(function () {
    Route::get('/auth/sign-in', [Auth::class, 'index'])->name('user.auth.index');

    Route::post('/auth/sign_in', [Auth::class, 'sign_in'])->name('user.auth.sign_in');

    Route::get('/auth/sign-up', [Auth::class, 'register'])->name('user.auth.register');

    Route::post('/auth/sign_up', [Auth::class, 'sign_up'])->name('user.auth.sign_up');

    Route::get('/auth/validate-email', [Auth::class, 'validate_email'])->name('user.auth.validate_email');

    Route::post('/auth/validate_email', [Auth::class, 'validating_email'])->name('user.auth.validating_email');

    Route::get('/auth/validate_email_send_again', [Auth::class, 'validating_email_send_again'])->name('user.auth.validating_email_send_again');

    Route::get('/auth/sign-in/forget-password', [Auth::class, 'forget_password_index'])->name('user.auth.forget_password');

    Route::post('/auth/sign-in/forget-password-email-input', [Auth::class, 'forget_password_email_input'])->name('user.auth.forget_password_email_input');

    Route::get('/auth/sign-in/forget-password/email-confirm', [Auth::class, 'forget_password_validate_email'])->name('user.auth.forget_password_email');

    Route::post('/auth/sign-in/forget-password/email-confirm', [Auth::class, 'forget_password_validating_email'])->name('user.auth.forget_password_email_confirm');

    Route::match(['get', 'post'], '/auth/sign-in/forget-password/new-password', [Auth::class, 'forget_password_new_password'])->name('user.auth.forget_password_new');
})->middleware(AuthBackHomeMiddleware::class);

Route::get('/auth/sign_out', [Auth::class, 'sign_out'])->name('user.auth.sign_out');

Route::group(function () {
    Route::get('/course/{id}/enroll', [Enrollment::class, 'index'])
        ->name('user.enroll.index')
        ->where('id', '^[0-9]*$');
    Route::get('/course/{id}/enroll/status', [Enrollment::class, 'payment_status'])
        ->name('user.enroll.payment_status');
})->middleware(AuthHasLoginedMiddleware::class);





Route::fallback(function () {
    $data = [
        'view' => 'errors/404',
        'page-title' => "Vui Hoc AI - Page not found"
    ];
    return View::render('layouts/user_layout', $data);
});

// Just for testing
Route::get('/test', function () {
    $mail = new PHPMailer(true);
    var_dump($mail);
});
