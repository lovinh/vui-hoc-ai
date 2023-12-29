@php
use function app\core\helper\route_url;
$course = $data['course'];
$lesson = $data['lesson'];
$sections = $data['sections'];
@endphp
<ol class="breadcrumb">
    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.detail', ['course_id' => $course['course_id']]) }}">{{ $course['course_name'] }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ route_url('author.course.lesson-detail', ['course_id' => $course['course_id'], 'lesson_id' => $lesson['lesson_id']]) }}">{{ $lesson['lesson_title'] }}</a></li>
</ol>