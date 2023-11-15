@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$model = $data['model'];

@endphp
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center" style="height: 300px !important;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2 class="text-white">{{ $model['section_name'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->
<!--================ Start Course Details Area =================-->
<section class="course_details_area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 right-contents">
                <ul>
                    @foreach ($model['lesson'] as $key => $lesson)
                    <li>
                        <a class="justify-content-between d-flex" data-toggle="collapse" href="#collapse{{ $key }}" role="button" aria-expanded="false" aria-controls="collapse{{ $key }}">
                            {{ ($key + 1) . '. ' . ($lesson['lesson_name']) }}
                        </a>
                        <div class="collapse {{ $lesson['lesson_id'] == $model['lesson_id'] ? 'show' : false }}" id="collapse{{ $key }}">
                            <ul>
                                @foreach ($lesson['sections'] as $sec_key => $section)
                                <li>
                                    <a class="justify-content-between d-flex" href="{{ route_url('user.learning.detail', ['course_id' => $model['course_id'], 'lesson_id' => $lesson['lesson_id'], 'section_id' => $section['section_id']]) }}" style="display: flex; {! $model['section_id'] == $section['section_id'] ? 'text-decoration: underline;' : false !}">
                                        {{ ($key + 1) . '.' . ($sec_key + 1) . '. ' . $section['section_name'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>

            </div>

            <div class="col-lg-8 course_details_left">
                <div class="content_wrapper">
                    @if (empty($model['section_content']))
                    <p class="text-center">No content!</p>
                    @else
                    {! $model['section_content'] !}
                    @endif
                </div>
            </div>



        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->