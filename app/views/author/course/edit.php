@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;

$course = $data['course'];
$errors = $data['errors'];
$error = $data['error'];
$current = $data['current'];

@endphp
<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Edit {{ $course['course_name'] }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.course.index') }}">Course List</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.detail', ['course_id' => $data['course']['course_id']]) }}">{{ $course['course_name'] }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.edit', ['course_id' => $data['course']['course_id']]) }}">Edit</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route_url('author.course.editing', ['course_id' => $data['course']['course_id']]) }}" id="step-form-horizontal" class="step-form-horizontal" method="post" enctype="multipart/form-data">
                            <div>
                                <h4>Course Name</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="course_name" class="form-control form-control-lg" value="{{ empty($current['course_name']) ? $course['course_name'] : $current['course_name'] }}" required>
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
                                            <div class="form-group">
                                                <textarea name="course_description" id="course_description" class="form-control" cols="30" rows="30">{{ empty($current['course_description']) ? $course['course_description'] : html_entity_decode($current['course_description']) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="form-group">
                                                <h4>Course subject</h4>
                                                <select class="js-example-basic-single" name="course_subject_id">
                                                    @if (!empty($data['subjects']))
                                                    @foreach($data['subjects'] as $subject)
                                                    <option value="{{ $subject['subject_id'] }}" {{ $subject['subject_id'] == $course['course_subject_id'] ? 'selected' : false }}>{{ $subject['subject_name'] }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @if (!empty($errors['course_subject']))
                                                <label class="text-label" for="course_subject" style="color: red;">{{ $errors['course_subject'] }}</label>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <h4>Course price</h4>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="course_price" id="course_price" value="{{ $current['course_price'] ?? $course['course_price'] }}" required>
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
                                            <h4>Course thumbnail</h4>
                                            <div class="input-group mb-3">
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
                                            <h4>Course banner</h4>
                                            <div class="input-group mb-3">
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
                                <h4>Course Update Description</h4>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <div class="form-group">
                                                @if (empty($current['course_update_description']))
                                                <textarea name="course_update_description" id="course_update_description" class="form-control" cols="30" rows="30"></textarea>
                                                @else
                                                <textarea name="course_update_description" id="course_update_description" class="form-control" cols="30" rows="30">
                                                {{ html_entity_decode($current['course_update_description']) }}
                                                </textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <a href="{{  route_url('author.course.detail', ['course_id' => $course['course_id']]) }}" class="btn btn-outline-primary pl-3 pr-3">Back</a>
                                            <button type="submit" class="btn btn-success pl-3 pr-3">Save</button>
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