@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$description = $data['lesson']['lesson_description']

@endphp
<div class="card-header">
    <h4 class="mb-0">Description</h4>
    <a href="{{ route_url('author.course.edit-description', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id']]) }}" class="btn btn-primary">Edit</a>
</div>
<div class="card-body {{ empty($description) ? 'text-center' : false }}">
    @if (empty($description))
    <span class="text-center">No description</span>
    @else
    {! $description !}
    @endif
</div>