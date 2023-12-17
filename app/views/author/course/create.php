@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;


$errors = $data['errors'];
$error = $data['error'];
$current = $data['current'];

@endphp
<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Create new course</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.course.index') }}">Course List</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.new') }}">Create Course</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create New Course - Step</h4>
                        @if (!empty($errors))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Error!</strong> Validation failed. Please check again!
                        </div>
                        @endif
                        @if (!empty($error))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Error!</strong> Cannot create new course. Please try again!
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ route_url('author.course.creating') }}" id="step-form-horizontal" class="step-form-horizontal" method="post" enctype="multipart/form-data">
                            <div>
                                <h4>Course Name</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <h1 class="text-center">Awesome <span style="color:#6b51df;">name</span> make your learners <span style="color:#6b51df;">curious</span>!</h1>
                                            <div class="text-center">Your course name is really important! It help your learners know what thing they earn after completing your course!</div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <label class="text-label">Enter your course name</label>
                                                <div class="input-group">
                                                    <input type="text" name="course_name" class="form-control form-control-lg" placeholder="Ex: Python For Beginner" value="{{ $current['course_name'] ?? false }}" required>
                                                </div>
                                                @if (!empty($errors['course_name']))
                                                <label class="text-label" for="course_name" style="color: red;">{{ $errors['course_name'] }}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <h4>Course Description</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <h1 class="text-center">Your course description - That's all you <span style="color:#6b51df;">teach</span>!</h1>
                                            <div class="text-center">Your course description is likely a summery of your course! It help your learners understand your exciting course even more clearly! Make sure you write you course description carefully.</div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <h4 class="text-label">Your Course Description</h4>
                                                @if (empty($current['course_description']))
                                                <textarea name="course_description" id="course_description" class="form-control" cols="30" rows="30"></textarea>
                                                @else
                                                <textarea name="course_description" id="course_description" class="form-control" cols="30" rows="30">
                                                    {{ html_entity_decode($current['course_description']) }}
                                                </textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <h4>Course Reference</h4>
                                <section>
                                    <div class="col-lg-12 mb-4">
                                        <h1 class="text-center">Course reference - Help your learners with more <span style="color:#6b51df;">information</span>!</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="form-group">
                                                <h4>Course subject *</h4>
                                                <input class="form-control" type="text" name="course_subject" id="course_subject" value="{{ $current['course_subject'] ?? 'Python' }}">
                                                @if (!empty($errors['course_subject']))
                                                <label class="text-label" for="course_subject" style="color: red;">{{ $errors['course_subject'] }}</label>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <h4>Course price *</h4>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="course_price" id="course_price" value="{{ $current['course_price'] ?? '0' }}" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">USD</span>
                                                </div>
                                            </div>
                                            @if (!empty($errors['course_price']))
                                            <label class="text-label" for="course_price" style="color: red;">{{ $errors['course_price'] }}</label>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <h4>Course thumbnail *</h4>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="course_thumbnail" accept="image/*">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                            </div>
                                            @if (!empty($errors['course_thumbnail']))
                                            <label class="text-label" for="course_thumbnail" style="color: red;">{{ $errors['course_thumbnail'] }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <h4>Course banner *</h4>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="course_banner" accept="image/*">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                            </div>
                                            @if (!empty($errors['course_banner']))
                                            <label class="text-label" for="course_banner" style="color: red;">{{ $errors['course_banner'] }}</label>
                                            @endif
                                        </div>
                                    </div>

                                </section>
                                <h4>Course Confirm</h4>
                                <section>
                                    <div class="col-lg-12 mb-4">
                                        <h1 class="text-center">Final Step - Let's your course <span style="color:#6b51df;">active</span>!</h1>
                                        <h4 class="mb-4">Your course setup is already done! Click <span style="color:#6b51df;">finish</span> to create your new draft course. Note that your new course will be in <span style="color:#6b51df;">draft</span> mode and be unavailable with other learners. To active your course, add section and lesson via course detail page and select active course.</h4>
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