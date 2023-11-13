@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$model = $data['model'];

$n_stars = round($model['rate']);

$rate_status = $n_stars > 3 ? "Recommend" : "Not Recommend";

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>{{ $model['name'] }}</h2>
                        <div class="page_link">
                            <a href="{{ route_url('user.home.index') }}">Home</a>
                            <a href="{{ route_url('user.course.index') }}">Courses</a>
                            <a href="{{ route_url('user.course.detail', ['id' => $model['id']]) }}">{{ $model['name'] }}</a>
                        </div>
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
                <div class="main_image">
                    <img class="img-fluid" src="{{ public_url('files/' . $model['banner']) }}" alt="">
                </div>
                <div class="content_wrapper">
                    <h4 class="title">Description</h4>
                    <div class="content">
                        {! $model['description'] !}
                    </div>
                    <h4 class="title">Course Lessons</h4>
                    <div class="content">
                        @if (!empty($model['lesson']))
                        <ul class="course_list">
                            @foreach ($model['lesson'] as $key=>$value)
                            <li class="justify-content-between d-flex">
                                <span class = "circle">{{ $key + 1 }}</span>
                                <span>{{ $value['lesson_title'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @else
                            <p class="text-center">No Lesson Found!</p>
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-lg-4 right-contents">
                <ul>
                    <li>
                        <a class="justify-content-between d-flex" href="#">
                                <div>Author</div>
                                <div class="or">{{ $model['author'] }}</div>
                        </a>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div>Course Fee </div>
                            <div> {{ $model['price'] == 0 ? "Free" : $model['price'] . '$' }} </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div>Last Update Date </div>
                            <div> {{ $model['last_update'] }} </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div> Belong To Subject </div>
                            <div>{{ $model['subject'] }}</div>
                        </div>
                    </li>
                </ul>
                @if (strtoupper($model['status']) == strtoupper("available"))
                <a href="{{ route_url('user.enroll.index', ['id' => $model['id']]) }}" class="primary-btn2 text-uppercase enroll rounded-0 text-white">Enroll the course</a>
                @else
                <a href="#" class="primary-btn2 text-uppercase enroll rounded-0 text-white unavailable">Course Unavailable</a>
                @endif
                <h4 class="title">Reviews</h4>
                <div class="content">
                    <div class="review-top row pt-40">
                        <div class="col-lg-12">
                            <h6 class="mb-15">Course Rating</h6>
                            <div class="d-flex flex-row reviews justify-content-between">
                                <span>Quality</span>
                                <div class="star">
                                    @for ($i = 1; $i <= $n_stars; $i++ )
                                    <i class="ti-star checked"></i>
                                    @endfor
                                    @for ($i = 1; $i <= 5 - $n_stars; $i++)
                                    <i class="ti-star"></i>
                                    @endfor
                                </div>
                                <span>{{ $rate_status }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="comments-area mb-30">
                        @if (!empty($model['reviews']))
                        @foreach ($model['reviews'] as $key => $value)
                        <div class="comment-list">
                            <div class="single-comment single-reviews justify-content-between d-flex">
                                <div class="user justify-content-between d-flex">
                                    <div class="thumb">
                                        <img src="{{ assets('user/img/blog/c1.jpg') }}" alt="">
                                    </div>
                                    <div class="desc">
                                        <h5><a href="#">{{ $value['user'] }}</a>
                                            @php
                                            $user_stars = round($value['rate']);
                                            @endphp 
                                            <div class="star">
                                                @for ($i = 1; $i <= $user_stars; $i++ )
                                                <i class="ti-star checked"></i>
                                                @endfor
                                                @for ($i = 1; $i <= 5 - $user_stars; $i++)
                                                <i class="ti-star"></i>
                                                @endfor
                                            </div>
                                        </h5>
                                        <p class="comment">
                                            {{ $value['content'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center">No review yet</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->