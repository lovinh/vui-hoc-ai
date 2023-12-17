@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
$message = $data['message'];
@endphp
<div class="content-body" style="min-height: 788px;">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>My Course List</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.course.index') }}">Course List</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        @if (!empty($message))
                        <div class="alert alert-success alert-dismissible alert-alt fade show">
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Success!</strong> {{ $message }}
                        </div>
                        @endif
                        <h4 class="card-title">All Courses</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm">
                                @if (empty($data['courses']))
                                <div class="text-center">No courses found!</div>
                                @else
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data['courses'] as $key => $course)
                                    <tr>
                                        <td><a href="{{ route_url('author.course.detail', ['course_id' => $course['course_id']]) }}">{{ $course['course_name'] }}</a></td>
                                        <td>{{ $course['subject_name'] }}</td>
                                        <td><span class="text-muted">{{ str_date($course['course_publish_date']) }}</span></td>
                                        <td><span class="badge {{ $course['course_status'] == 'available' ? 'badge-success' : ( $course['course_status'] == 'draft' ? 'badge-dark' : 'badge-danger') }}">{{ $course['course_status'] }}</span></td>
                                        <td>{{ $course['course_price'] }}</td>
                                        <td><a href="{{ route_url('author.course.detail', ['course_id' => $course['course_id']]) }}" class="btn btn-primary">view</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>