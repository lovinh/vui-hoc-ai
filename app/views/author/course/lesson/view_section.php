@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$section = $data['section'];

@endphp 
<div class="card-header">
    <h4 class="mb-0">{{ $section['section_name'] }}</h4>
    <a href="{{ route_url('author.course.edit-section', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id'], 'section_id' =>  $data['section']['section_id']]) }}" class="btn btn-primary">Edit</a>
</div>
<div class="card-body {{ empty($section['section_content']) ? 'text-center' : false }}">
    @if (empty($section['section_content']))
    <span class="text-center">No content</span>
    @else
    {! $section['section_content'] !}
    @endif
</div>