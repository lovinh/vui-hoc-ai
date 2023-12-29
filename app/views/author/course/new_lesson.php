@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;

$course = $data['course'];
$response = $data['response'];
$errors = $data['errors'];
$current = $data['current'];

@endphp
<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Add new lesson for {{ $course['course_name'] }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.course.index') }}">Course List</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.detail', ['course_id' => $data['course']['course_id']]) }}">{{ $course['course_name'] }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.new_lesson', ['course_id' => $data['course']['course_id']]) }}">New Lesson</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route_url('author.course.adding-lesson', ['course_id' => $data['course']['course_id']]) }}" id="step-form-horizontal" class="step-form-horizontal" method="post" enctype="multipart/form-data">
                            <div>
                                <h4>Lesson Name</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="lesson_name" class="form-control form-control-lg" value="{{ $current['lesson_name'] ?? false }}">
                                                </div>
                                                @if (!empty($errors['lesson_name']))
                                                <label class="text-label" for="lesson_name" style="color: red;">{{ $errors['lesson_name'] }}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <h4>Lesson Description</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <textarea name="lesson_description" id="lesson_description" class="form-control" cols="30" rows="30">{{ $current['lesson_description'] ?? false }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-primary pl-5 pr-5">Add</button>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>