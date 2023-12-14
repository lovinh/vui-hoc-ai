
<?php

use app\core\controller\user\Auth;
use app\core\controller\user\Course;
use app\core\controller\user\Dashboard;
use app\core\controller\user\Enrollment;
use app\core\controller\user\Home;
use app\core\controller\user\LearningCourse;
use app\core\controller\user\Profile;
use app\core\http_context\Response;
use app\core\middleware\user\AuthBackHomeMiddleware;
use app\core\middleware\user\AuthBackToSignInMiddleware;
use app\core\middleware\user\AuthHasLoginedMiddleware;
use app\core\middleware\user\HasActiveMiddleware;
use app\core\middleware\user\HasEnrolledMiddleware;
use app\core\middleware\user\IsValidCourseMiddleware;
use app\core\middleware\user\RedirectIfEnrolledMiddleware;
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
    ->where('id', '^[0-9]*$')
    ->middleware(RedirectIfEnrolledMiddleware::class, IsValidCourseMiddleware::class);

Route::get('/search', [Course::class, 'search_by_name'])->name('user.course.search_name');

// Dashboard

Route::get('/user/{id}/dashboard', [Dashboard::class, 'index'])
    ->name('user.dashboard.index')
    ->middleware(AuthHasLoginedMiddleware::class);

// Authentication
Route::group(function () {
    Route::get('/auth/sign-in', [Auth::class, 'index'])->name('user.auth.index');

    Route::post('/auth/sign_in', [Auth::class, 'sign_in'])->name('user.auth.sign_in');

    Route::get('/auth/sign-up', [Auth::class, 'register'])->name('user.auth.register');

    Route::post('/auth/sign_up', [Auth::class, 'sign_up'])->name('user.auth.sign_up');

    Route::get('/auth/sign-in/forget-password', [Auth::class, 'forget_password_index'])->name('user.auth.forget_password');

    Route::post('/auth/sign-in/forget-password-email-input', [Auth::class, 'forget_password_email_input'])->name('user.auth.forget_password_email_input');

    Route::get('/auth/sign-in/forget-password/email-confirm', [Auth::class, 'forget_password_validate_email'])->name('user.auth.forget_password_email');

    Route::post('/auth/sign-in/forget-password/email-confirm', [Auth::class, 'forget_password_validating_email'])->name('user.auth.forget_password_email_confirm');

    Route::match(['get', 'post'], '/auth/sign-in/forget-password/new-password', [Auth::class, 'forget_password_new_password'])->name('user.auth.forget_password_new');
})->middleware(AuthBackHomeMiddleware::class);

Route::get('/auth/validate-email', [Auth::class, 'validate_email'])->name('user.auth.validate_email')->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/auth/validate_email', [Auth::class, 'validating_email'])->name('user.auth.validating_email')->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/auth/validate_email_send_again', [Auth::class, 'validating_email_send_again'])->name('user.auth.validating_email_send_again')->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/auth/sign_out', [Auth::class, 'sign_out'])->name('user.auth.sign_out');

// Enrollment
Route::group(function () {
    Route::get('/course/{id}/enroll', [Enrollment::class, 'index'])
        ->name('user.enroll.index')
        ->where('id', '^[0-9]*$');
    Route::post('/course/{id}/enrolling', [Enrollment::class, 'payment'])
        ->where('id', '^[0-9]*$')
        ->name('user.enroll.payment');
    Route::get('/course/{id}/enroll/status', [Enrollment::class, 'payment_status'])
        ->name('user.enroll.payment_status')
        ->where('id', '^[0-9]*$');
})->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class]);

// Learning

Route::get('/learning/{id}/introduction', [LearningCourse::class, 'index'])
    ->name('user.learning.intro')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::get('/learning/{course_id}/detail/{lesson_id}/{section_id}', [LearningCourse::class, 'learn_section'])
    ->name('user.learning.detail')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->where('section_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class,  HasEnrolledMiddleware::class]);

Route::get('/learning/{id}/progress', [LearningCourse::class, 'progress'])
    ->name('user.learning.progress')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::get('/learning/{id}/note', [LearningCourse::class, 'note'])
    ->name('user.learning.note')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::get('/learning/{id}/note/{note_id}/detail', [LearningCourse::class, 'note_detail'])
    ->name('user.learning.note_detail')
    ->where('id', '^[0-9]*$')
    ->where('note_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::get('/learning/{id}/note/create', [LearningCourse::class, 'create_note'])
    ->name('user.learning.create_note')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::post('/learning/{id}/note/creating', [LearningCourse::class, 'creating_note'])
    ->name('user.learning.creating_note')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::post('/learning/{id}/note/{note_id}/deleting', [LearningCourse::class, 'deleting_note'])
    ->name('user.learning.deleting_note')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class,  HasEnrolledMiddleware::class]);

Route::get('/learning/{id}/note/{note_id}/edit', [LearningCourse::class, 'edit_note'])
    ->name('user.learning.edit_note')
    ->where('id', '^[0-9]*$')
    ->where('note_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

Route::post('/learning/{id}/note/{note_id}/editing', [LearningCourse::class, 'editing_note'])
    ->name('user.learning.editing_note')
    ->where('id', '^[0-9]*$')
    ->where('note_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class, HasActiveMiddleware::class, HasEnrolledMiddleware::class]);

// Profile
Route::get('/user/{id}/profile', [Profile::class, 'index'])
    ->name('user.profile.index')
    ->where('id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);


// Exception
Route::fallback(function () {
    $data = [
        'view' => 'errors/404',
        'page' => '404',
        'page-title' => "Vui Hoc AI - Page not found",
        'home' => true,
    ];
    return View::render('layouts/user_layout', $data)->response_code(404);
});

// Just for testing
Route::post('/test', function () {
    $data = [
        'view' => 'test',
        'page-title' => "Test - Vui Hoc AI",
    ];
    return View::render('layouts/user_learning_layout', $data);
})->name('test');
Route::get('/hey', function () {
    return "Ok it good!";
});
