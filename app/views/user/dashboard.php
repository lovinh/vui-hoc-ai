@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$model = $data['model'];

$course_count = 0;
if (!empty($model['courses']))
{
$course_count = count($model['courses']);
}
$n_rows = $course_count % 3 == 0 ? intdiv($course_count, 3) : intdiv($course_count, 3) + 1;
$course_remain = $course_count % 3;

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>{{ 'Your dashboard' }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->
<!--================ Start All Courses Area ===================-->

<div class="popular_courses section_gap_top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="main_title">
                    <h2 class="mb-3">You have enrolled {{ $course_count }} courses!</h2>
                    @if ($course_count == 0)
                    <p class="mb-3">Hey! Let's start your journey by enrolling some coures <i class="far fa-sad-tear"></i></p>
                    @else 
                    <p class="mb-3">Check it now!</p>
                    @endif
                </div>
            </div>
        </div>
        @if ($course_count != 0)
        @for ($i=0; $i < $n_rows; $i++) 
        <div class="row p-3">
            @for ($k = 0; $k < 3; $k++) 
            @if (array_key_exists($i * 3 + $k, $model['courses']))
             <div class="col-lg-4">
                <div class="single_course">
                    <div class="course_head">
                        <img class="img-fluid" src="{{ empty($model['courses'][$i * 3 + $k]['course_thumbnail']) ? assets('user/img/default-course-thumbnail.png') : public_url('files/' . $model['courses'][$i * 3 + $k]['course_thumbnail']) }}" alt="" style="height: 300px; object-fit: cover;" />
                    </div>
                    <div class="course_content">
                        <span class="tag mb-4 d-inline-block">{{ $model['courses'][$i * 3 + $k]['course_subject'] }}</span>
                        <h4 class="mb-3">
                            <a href="{{ route_url('user.course.detail', ['id' => $model['courses'][$i * 3 + $k]['course_id']]) }}">{{ $model['courses'][$i * 3 + $k]['course_name'] }}</a>
                        </h4>

                        <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                            <div class="authr_meta">
                                <img src="{{ empty($model['courses'][$i * 3 + $k]['course_author_avt']) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $model['courses'][$i * 3 + $k]['course_author_avt']) }}" alt="" style="width: 45px; height: 45px; border-radius: 50%" />
                                <span class="d-inline-block ml-2">{{ $model['courses'][$i * 3 + $k]['course_author'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            @endif 
        @endfor
    </div>
    @endfor
    @endif
    </div>
</div>

<!--================ End All Courses Area ===================-->