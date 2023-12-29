@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$description = $data['lesson']['lesson_description']

@endphp
<form action="{{ route_url('author.course.editing-description', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id']]) }}" method="post">
    <div class="card-header">
        <h4 class="mb-0">Edit Description</h4>
        <div>
            <a href="{{ route_url('author.course.lesson-detail', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id']]) }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
    <div class="card-body {{ empty($description) ? 'text-center' : false }}">
        <textarea name="lesson_description" id="lesson_description" class="form-control" cols="30" rows="30">
        {{ html_entity_decode($data['lesson']['lesson_description']) ?? false }}
        </textarea>
    </div>
</form>