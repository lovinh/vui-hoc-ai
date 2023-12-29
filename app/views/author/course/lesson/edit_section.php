@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\str_datetime;

$section = $data['section'];
$current = $data['current'];

@endphp
<form action="{{ route_url('author.course.editing-section', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id'], 'section_id' => $section['section_id']]) }}" method="post">
    <div class="card-header">
        <h4 class="mb-0">Edit Section</h4>
        <div>
            <a href="{{ route_url('author.course.view-section', ['course_id' => $data['course']['course_id'], 'lesson_id' => $data['lesson']['lesson_id'], 'section_id' => $section['section_id']]) }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="section_name" class="form-control form-control-lg" value="{{ empty($current['section_name']) ? $section['section_name'] : $current['section_name'] }}" placeholder="Your section name" required>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <textarea name="section_content" id="section_content" class="form-control m-3" cols="30" rows="30">
                {! empty($current['section_content']) ? $section['section_content'] : $current['section_content'] !}
                </textarea>
            </div>
        </div>
    </div>
</form>