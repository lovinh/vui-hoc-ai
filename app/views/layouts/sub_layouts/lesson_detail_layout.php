@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;
use function app\core\helper\render_block;

$course = $data['course'];
$lesson = $data['lesson'];
$sections = $data['sections'];
$response = $data['response'];
@endphp

<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ $lesson['lesson_title'] }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                @php
                render_block($data['sub_nav_block'], $data);
                @endphp 
            </div>
        </div>
        <!-- row -->
        <div class="row pl-3">
            <div class="col-lg-4">
                <div class="row">
                    <div class="card mb-3" style="width: 100%;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Sections</h4>
                            <form action="{{ route_url('author.course.adding-section', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id']]) }}" method="post">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="basic-list-group">
                                <ul class="list-group {{ empty($sections) ? 'text-center' : false }}">
                                    @if (empty($sections))
                                    <span>No section</span>
                                    @else
                                    @foreach ($sections as $section)
                                    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-3">
                                        <span>
                                            <a href="{{ route_url('author.course.view-section', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id'], 'section_id' => $section['section_id']]) }}" class="text-dark">
                                                {{ $section['section_name'] }}
                                            </a>
                                        </span>
                                        <span>
                                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#section_delete_modal_{{$section['section_id']}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </span>

                                    </li>
                                    <div class="modal fade" id="section_delete_modal_{{$section['section_id']}}" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure want to delete this section? After your confirmation, this section will be deleted completly. You won't be able to restore this section.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <form action="{{ route_url('author.course.deleting-section', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id'], 'section_id' => $section['section_id']]) }}" method="post">
                                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card mb-3" style="width: 100%;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Lesson Test</h4>
                            <a href="#" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card mb-3" style="width: 100%;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Description</h4>
                            <a href="{{ route_url('author.course.lesson-detail', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id']]) }}" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="card" style="width: 100%;">
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#course_delete_model">Delete Lesson</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card" style="width: 100%;">
                    @php
                    render_block($data['sub_layouts'], $data);
                    @endphp
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
                        <p>Are you sure want to delete this lesson? After your confirmation, this lesson will be deleted completly. You would not be able to restore this lesson.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route_url('author.course.deleting-lesson', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id']]) }}" method="post">
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>