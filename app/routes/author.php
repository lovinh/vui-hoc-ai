
<?php

use app\core\controller\author\Course as AuthorCourse;
use app\core\controller\author\Home as AuthorHome;
use app\core\middleware\user\AuthHasLoginedMiddleware;
use app\core\Route;

// <=== AUTHOR ===>

Route::get('/author/dashboard', [AuthorHome::class, 'index'])
    ->name('author.home.index')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course', [AuthorCourse::class, 'index'])
    ->name('author.course.index')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/available', [AuthorCourse::class, 'available'])
    ->name('author.course.available')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/draft', [AuthorCourse::class, 'draft'])
    ->name('author.course.draft')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/{course_id}/detail', [AuthorCourse::class, 'detail'])
    ->name('author.course.detail')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/{course_id}/active', [AuthorCourse::class, 'active'])
    ->name('author.course.active')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/{course_id}/deactive', [AuthorCourse::class, 'deactive'])
    ->name('author.course.deactive')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/new-course', [AuthorCourse::class, 'new'])
    ->name('author.course.new')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/creating', [AuthorCourse::class, 'create'])
    ->name('author.course.creating')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/{course_id}/edit', [AuthorCourse::class, 'edit'])
    ->name('author.course.edit')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/{course_id}/editing', [AuthorCourse::class, 'editing'])
    ->name('author.course.editing')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/{course_id}/deleting', [AuthorCourse::class, 'deleting'])
    ->name('author.course.deleting')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/course/{course_id}/new-lesson', [AuthorCourse::class, 'add_lesson'])
    ->name('author.course.new_lesson')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/course/{course_id}/adding-lesson', [AuthorCourse::class, 'adding_lesson'])
    ->name('author.course.adding-lesson')
    ->where('course_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/{course_id}/lesson/{lesson_id}/delete-lesson', [AuthorCourse::class, 'deleting_lesson'])
    ->name('author.course.deleting-lesson')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/{course_id}/lesson/{lesson_id}/detail', [AuthorCourse::class, 'lesson_detail'])
    ->name('author.course.lesson-detail')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/{course_id}/lesson/{lesson_id}/edit-lesson-description', [AuthorCourse::class, 'edit_description'])
    ->name('author.course.edit-description')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/{course_id}/lesson/{lesson_id}/editing-lesson-description', [AuthorCourse::class, 'editing_description'])
    ->name('author.course.editing-description')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/{course_id}/lesson/{lesson_id}/adding-section', [AuthorCourse::class, 'adding_section'])
    ->name('author.course.adding-section')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/{course_id}/{lesson_id}/section/{section_id}/edit', [AuthorCourse::class, 'edit_section'])
    ->name('author.course.edit-section')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->where('section_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/{course_id}/{lesson_id}/section/{section_id}/editing', [AuthorCourse::class, 'editing_section'])
    ->name('author.course.editing-section')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->where('section_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::post('/author/{course_id}/{lesson_id}/section/{section_id}/deleting', [AuthorCourse::class, 'deleting_section'])
    ->name('author.course.deleting-section')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->where('section_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

Route::get('/author/{course_id}/{lesson_id}/section/{section_id}/view', [AuthorCourse::class, 'view_section'])
    ->name('author.course.view-section')
    ->where('course_id', '^[0-9]*$')
    ->where('lesson_id', '^[0-9]*$')
    ->where('section_id', '^[0-9]*$')
    ->middleware([AuthHasLoginedMiddleware::class]);

// <=== END AUTHOR ===>