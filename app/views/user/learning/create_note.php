@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;
use function app\core\helper\str_datetime;

$model = $data['model'];


@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Create note</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->
<!--================ Start Course Details Area =================-->
<section class="course_details_area section_gap">
    <div class="container">
        <form class="row" action="{{ route_url('user.learning.creating_note', ['id' => $model['course_id']]) }}" method="post">
            <div class="col-md-8">
                <h3>Take note here</h3>
                <div class="note-edit-content">
                    <div class="card">
                        <h5 class="card-header">
                            <i class="fas fa-calendar-week"></i>
                            <span>Note content</span>
                        </h5>
                        <div class="card-body">
                            <div class="card-text note-create-content">
                                <textarea name="note_content" id="note_content" class="form-control" cols="30" rows="30"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 right-contents">
                <h3>Are you done?</h3>
                <button type="submit" class="genric-btn info-border p-1 text-uppercase enroll rounded-0">Save note</button>
                <a href="{{ route_url('user.learning.note', ['id' => $model['course_id']]) }}" class="genric-btn primary-border p-1 text-uppercase enroll rounded-0">Back to notes list</a>
            </div>
        </form>
    </div>
</section>
<!--================ End Course Details Area =================-->