@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;
$course_count = 0;
if (!empty($data['courses']))
{
$course_count = count($data['courses']);
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
                        <h2>Courses</h2>
                        <div class="page_link">
                            <a href="{{ route_url('user.home.index') }}">Home</a>
                            <a href="{{ route_url('user.course.index') }}">Courses</a>
                        </div>
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
                    <h2 class="mb-3">{{ $data['course_result_title'] }}</h2>
                </div>
            </div>
        </div>
        @if ($course_count != 0)
        @for ($i=0; $i < $n_rows; $i++) <div class="row p-3">
            <!-- single course -->

            @for ($k = 0; $k < 3; $k++) @if (array_key_exists($i * 3 + $k, $data['courses']))
             <div class="col-lg-4">
                <div class="single_course">
                    <div class="course_head">
                        <img class="img-fluid" src="{{ empty($data['courses'][$i * 3 + $k]['thumbnail']) ? assets('user/img/default-course-thumbnail.png') : public_url('files/' . $data['courses'][$i * 3 + $k]['thumbnail']) }}" alt="" style="height: 300px; object-fit: cover;" />
                    </div>
                    <div class="course_content">
                        <span class="price">{{ $data['courses'][$i * 3 + $k]['price'] == 0 ?  'Free' : $data['courses'][$i * 3 + $k]['price'] . '$' }}</span>
                        <span class="tag mb-4 d-inline-block">{{ $data['model']->get_course_subject($data['courses'][$i * 3 + $k]['id']) }}</span>
                        <h4 class="mb-3">
                            <a href="{{ route_url('user.course.detail', ['id' => $data['courses'][$i * 3 + $k]['id']]) }}">{{ $data['courses'][$i * 3 + $k]['name'] }}</a>
                        </h4>

                        <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                            <div class="authr_meta">
                            <img src="{{ empty($data['model']->get_course_author_avatar($data['courses'][$i * 3 + $k]['id'])) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $data['model']->get_course_author_avatar($data['courses'][$i * 3 + $k]['id'])) }}" alt="" style="width: 45px; height: 45px; border-radius: 50%" />
                                <span class="d-inline-block ml-2">{{ $data['model']->get_course_author($data['courses'][$i * 3 + $k]['id']) }}</span>
                            </div>
                            <div class="mt-lg-0 mt-3">
                                <span class="meta_info mr-4">
                                    <i class="ti-user mr-2"></i>25
                                </span>
                                <span class="meta_info"> <i class="ti-heart mr-2"></i>35 </span>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    @endif
    @endfor
</div>
@endfor
@else
<div class="row p-3">
    <div class="col-lg-12">
        <div class="main_title">
            <h3 class="mb-3">No courses found!</h3>
        </div>
    </div>
</div>
@endif
</div>
</div>

<!--================ End All Courses Area ===================-->

<!--================ Start Feature Area =================-->
<section class="feature_area section_gap_top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="main_title">
                    <h2 class="mb-3">Awesome Feature</h2>
                    <p>
                        Something that fun about this course are given for you!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single_feature">
                    <div class="icon"><span class="flaticon-student"></span></div>
                    <div class="desc">
                        <h4 class="mt-3 mb-2">Earn Knowledges</h4>
                        <p>
                            Website give you tons of courses from professional author that have experiences in AI and Machine Learning. You can learn almost anything from our courses.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single_feature">
                    <div class="icon"><span class="flaticon-book"></span></div>
                    <div class="desc">
                        <h4 class="mt-3 mb-2">Online Course</h4>
                        <p>
                            Free! Everytime! Everywhere! </br>
                            Don't have to worry about your time and your learning place because you can learn from anywhere and anytime. Just need you to connect with the Internet, the world is your!
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single_feature">
                    <div class="icon"><span class="flaticon-earth"></span></div>
                    <div class="desc">
                        <h4 class="mt-3 mb-2">Global Certification</h4>
                        <p>
                            After you complete a course, a certification will be created for you to certificate your new learning skills.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Feature Area =================-->