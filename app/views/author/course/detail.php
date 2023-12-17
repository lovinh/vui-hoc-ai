@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$course = $data['course'];
$error = $data['error'];
$message = $data['message'];
@endphp
<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ $course['course_name'] }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.course.index') }}">Course List</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.detail', ['course_id' => $data['course']['course_id']]) }}">{{ $course['course_name'] }}</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-statistics">
                            <div class="text-center border-bottom-1 pb-3">
                                <div class="row">
                                    <div class="col">
                                        <img src="{{ !empty($course['course_thumbnail']) ? public_url('files/'. $course['course_thumbnail'] ) : public_url('files/default-course-thumbnail.png') }}" alt="" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-blog pt-3 border-bottom-1 pb-1">
                            <div class="row mt-3">
                                <div class="col">
                                    <h4 class="text-primary d-inline">Subject:</h4>
                                </div>
                                <div class="col text-right">
                                    <h4>{{ $course['course_subject'] }}</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <h4 class="text-primary d-inline">Last update:</h4>
                                </div>
                                <div class="col text-right">
                                    <h4>{{ $course['course_last_update'] }}</h4>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <h4 class="text-primary d-inline">Price:</h4>
                                </div>
                                <div class="col text-right">
                                    <h4>{{ $course['course_price'] == 0 ? "Free" : $course['course_price'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 pb-2 border-bottom-1 text-center">
                            <div class="">
                                <a href="{{ route_url('author.course.edit', ['course_id' => $data['course']['course_id']]) }}" class="btn btn-primary mr-3 ml-5">Edit</a>
                            </div>
                            <div class="">
                                <a href="javascript:void()" class="btn btn-dark mr-3">Active</a>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#course_delete_model">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if (!empty($error))
                        <div class="alert alert-danger alert-dismissible alert-alt fade show">
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Error!</strong> {{$error}}.
                        </div>
                        @elif (!empty($message))
                        <div class="alert alert-success alert-dismissible alert-alt fade show">
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Success!</strong> {{ $message }}.
                        </div>
                        @endif
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#course_descriptioin" data-toggle="tab" class="nav-link show active">Description</a>
                                    </li>
                                    <li class="nav-item"><a href="#course_lessons" data-toggle="tab" class="nav-link">Lessons</a>
                                    </li>
                                    <li class="nav-item"><a href="#course_update" data-toggle="tab" class="nav-link">Update Information</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="course_descriptioin" class="tab-pane fade active show">
                                        @if (!empty($course['course_banner']))
                                        <div class="pt-3">
                                            <img src="{{ public_url('files/' . $course['course_banner']) }}" alt="" style="width: 100%;">
                                        </div>
                                        @endif
                                        <div class="pt-3">
                                            {! $course['course_description'] ?? "No description" !}
                                        </div>
                                    </div>
                                    <div id="course_lessons" class="tab-pane fade">
                                        <div class="card-body {{ empty($course['course_lessons']) ? 'text-center' : false }}">
                                            @if (!empty($course['course_lessons']))
                                            <div id="accordion-nine" class="accordion accordion-active-header">
                                                @foreach ($course['course_lessons'] as $key => $lesson)
                                                <div class="accordion__item">
                                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#active-header_collapse{{$key}}" aria-expanded="false">
                                                        <span class="accordion__header--icon"></span>
                                                        <span class="accordion__header--text">{{ $lesson['lesson_title'] }}</span>
                                                        <span class="accordion__header--indicator"></span>
                                                    </div>
                                                    <div id="active-header_collapse{{$key}}" class="accordion__body collapse" data-parent="#accordion-nine">
                                                        <div class="accordion__body--text">
                                                            <h4 class="mt-3">Description:</h4>
                                                            {! $lesson['lesson_description'] !}
                                                            <h4 class="mt-3">Sections:</h4>
                                                            <div class="basic-list-group mt-3">
                                                                @if (!empty($course['course_sections'][$lesson['lesson_id']]))
                                                                <div class="list-group">
                                                                    @foreach ($course['course_sections'][$lesson['lesson_id']] as $key => $section)
                                                                    <a href="javascript:void()" class="list-group-item list-group-item-action"> {{ $section['section_name'] }} </a>
                                                                    @endforeach
                                                                </div>
                                                                @else
                                                                No section
                                                                @endif
                                                            </div>
                                                            <div class="mt-3">
                                                                <a href="#" class="btn btn-outline-primary pr-5 pl-5">Edit</a>
                                                                <a href="#" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            No lesson found
                                            @endif
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="#" class="btn btn-primary">Add Lesson</a>
                                        </div>
                                    </div>
                                    <div id="course_update" class="tab-pane fade">
                                        <div class="table-responsive mt-3">
                                            <table class="table table-responsive-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Time</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($course['course_updates'] as $index => $update)
                                                    <tr>
                                                        <th>{{ $index + 1 }}</th>
                                                        <td>{{ str_datetime($update['course_update_time']) }}</td>
                                                        <td>{! $update['course_update_description'] !}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="course_delete_model" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm delete</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure want to delete this course? After your confirmation, this course will be deleted completly. You would not restore the course.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route_url('author.course.deleting', ['course_id' => $data['course']['course_id']]) }}" method="post">
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>