@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$course = $data['course'];
$response = $data['response'];

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
                                    <h4>{{ $course['subject_name'] }}</h4>
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
                                    <h4>{{ $course['course_price'] == 0 ? "Free" : $course['course_price'] . ' USD' }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 pb-2 border-bottom-1 text-center">
                            <div class="col-lg-4 mt-3" style="padding: 0px 10px;">
                                <a href="{{ route_url('author.course.edit', ['course_id' => $data['course']['course_id']]) }}" class="btn btn-primary" style="width: 100%;">Edit</a>
                            </div>
                            <div class="col-lg-4 mt-3" style="padding: 0px 10px;">
                                @if (strtolower($course['course_status']) != 'available')
                                @if (!$course['can_active'])
                                <span class="btn btn-dark" style="width: 100%; cursor: not-allowed;">Active</span>
                                @else
                                <form action="{{ route_url('author.course.active', ['course_id' => $course['course_id']]) }}" method="post">
                                    <button type="submit" class="btn btn-success" style="width: 100%;">Active</button>
                                </form>
                                @endif
                                @else
                                <form action="{{ route_url('author.course.deactive', ['course_id' => $course['course_id']]) }}" method="post">
                                    <button type="submit" class="btn btn-warning" style="width: 100%;">Deactive</button>
                                </form>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-3" style="padding: 0px 10px;">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#course_delete_model" style="width: 100%;">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
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
                                            <div class="basic-list-group">
                                                <div class="list-group">
                                                    @foreach ($course['course_lessons'] as $key => $lesson)
                                                    <a href="{{ route_url('author.course.lesson-detail', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id']]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-3">
                                                        <span class="d-flex align-items-center">
                                                            {{ $lesson['lesson_title'] }}
                                                        </span>
                                                        <span class="badge badge-primary badge-pill">
                                                            {{ $course['course_sections'][$lesson['lesson_id']] }}
                                                        </span>
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @else
                                            No lesson found
                                            @endif
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="{{ route_url('author.course.new_lesson', ['course_id' => $data['course']['course_id']]) }}" class="btn btn-primary">Add Lesson</a>
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
                                                    @if (empty($course['course_updates']))
                                                    <tr>
                                                    </tr>
                                                    @else
                                                    @foreach ($course['course_updates'] as $index => $update)
                                                    <tr>
                                                        <th>{{ $index + 1 }}</th>
                                                        <td>{{ str_datetime($update['course_update_time']) }}</td>
                                                        <td>{! $update['course_update_description'] !}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
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