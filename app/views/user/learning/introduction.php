@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$model = $data['model'];

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Course Introduction</h2>
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
            <div class="col-lg-8 course_details_left">
                @if (!empty($model['banner']))
                <div class="main_image">
                    <img class="img-fluid" src="{{ public_url('files/' . $model['banner']) }}" alt="">
                </div>
                @endif
                <div class="content_wrapper">
                    <h4 class="title">Description</h4>
                    <div class="content">
                        {! $model['description'] !}
                    </div>
                    <h4 class="title">Course Lessons</h4>
                    <div class="content">
                        <ul class="course_list">
                            @if (empty($model['lesson']))
                            <p class="text-center">No Lesson Found!</p>
                            @else
                            @foreach ($model['lesson'] as $key => $lesson)
                            <li class="d-flex" style="flex-direction: column; ">
                                <a class="justify-content-between d-flex" data-toggle="collapse" href="#{{ 'lesson' . $key }}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <span class="circle">{{ $key + 1 }}</span>
                                    <span style="color: #7b838a;">{{ $lesson['lesson_name'] }}</span>
                                    @if ($model['process']->is_lesson_complete($model['user_id'], $model['course_id'], $lesson['lesson_id']))
                                    <i class="fas fa-check"></i>
                                    @else
                                    <i class="fas fa-times"></i>
                                    @endif
                                </a>
                                <div class="collapse m-3" id="{{ 'lesson' . $key }}">
                                    <ul>
                                        @foreach ($lesson['sections'] as $sec_key => $section)
                                        <li>
                                            <a class="justify-content-between d-flex" href="{{ route_url('user.learning.detail', ['course_id' => $model['course_id'], 'lesson_id' => $lesson['lesson_id'], 'section_id' => $section['section_id']]) }}">
                                                <span style="color: #7b838a;">{{ ($key + 1) . '. ' . ($sec_key + 1) }}</span>
                                                <span style="color: #7b838a;">{{ $section['section_name'] }}</span>
                                                @if ($model['process']->is_complete($model['user_id'], $model['course_id'], $lesson['lesson_id'], $section['section_id']))
                                                <i class="fas fa-times"></i>
                                                @else
                                                <i class="fas fa-check"></i>
                                                @endif
                                            </a>
                                            <hr />
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 right-contents">
                <ul>
                    <h3>Pick up where you left off</h3>
                    <!-- <br> -->
                    @if (strtolower($model['status']) != 'available')
                    <a href="#" class="primary-btn2 text-uppercase enroll rounded-0 text-white unavailable">Course Unavailable</a>
                    @else
                    <a href="{{ route_url('user.learning.detail', ['course_id' => $model['course_id'], 'lesson_id' => $model['nearest_progress']['lesson_id'], 'section_id' => $model['nearest_progress']['section_id']]) }}" class="genric-btn info p-1 text-uppercase enroll rounded-0 text-white">{{ $model['begin']? 'Start learning' : 'Resume' }}</a>
                    @endif
                </ul>
                <ul>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div> Author: </div>
                            <div> {{ $model['author'] }} </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div>Last Update Date: </div>
                            <div>{{ $model['last_update'] }} </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div> Belong To Subject: </div>
                            <div> {{ $model['subject'] }} </div>
                        </div>
                    </li>
                    <li>
                        <div style="font-weight: 400;">Related Course</div>
                        <ul>
                            @if (empty($mode['related_course']))
                            <div class="text-center m-3">No course related yet!</div>
                            @else
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, corporis!</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>


            </div>
        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->