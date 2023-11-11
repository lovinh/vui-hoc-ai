@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$newest_courses = $data['newest_courses'];

$author_model = $data['author'];

$authors = $author_model->get_authors(4);

@endphp
<!--================ Start Home Banner Area =================-->
<section class="home_banner_area">
    <div class="banner_inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner_content text-center">
                        <p class="text-uppercase">
                            YOUR AI - YOUR FUTURE
                        </p>
                        <h2 class="text-uppercase mt-4 mb-5">
                            CREATE YOUR CAREER WITH AI SKILLS
                        </h2>
                        <div>
                            <a href="#" class="primary-btn2 mb-3 mb-sm-0">learn more</a>
                            <a href="{{ route_url('user.course.index') }}" class="primary-btn ml-sm-3 ml-0">see course</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Home Banner Area =================-->

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
                <div class="single_feature" style="height: 320px;">
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
                <div class="single_feature" style="height: 320px;">
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
                <div class="single_feature" style="height: 320px;">
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

<!--================ Start Popular Courses Area =================-->
<div class="popular_courses">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="main_title">
                    <h2 class="mb-3">Newest Courses</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel active_course">
                    @if (empty($data['newest_courses']))
                    <div class="text-center">No course found</div>
                    @else
                    @foreach ($newest_courses as $course)
                    <div class="single_course">
                        <div class="course_head">
                            <img class="img-fluid" src="{{ empty($course['thumbnail']) ? assets('user/img/default-course-thumbnail.png') : public_url('files/' . $course['thumbnail']) }}" alt="" style="height: 300px; object-fit: cover;" />
                        </div>
                        <div class="course_content">
                            <span class="price">{{ $course['price'] != 0 ? $course['price'] . '$' : "Free" }}</span>
                            <span class="tag mb-4 d-inline-block">{{ $course['subject'] }}</span>
                            <h4 class="mb-3" style="height: 80px; overflow: hidden; white-space: no-wrap;text-overflow: ellipsis;">
                                <a href="{{ route_url('user.course.detail', ['id' => $course['id']]) }}">{{ $course['name'] }}</a>
                            </h4>
                            <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                                <div class="authr_meta ">
                                    <img src="{{ empty($author_model->get_author_avatar($course['author'])) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $author_model->get_author_avatar($course['author'])) }}" alt="" style="width: 45px; height: 45px; border-radius: 50%" />
                                    <span class="d-inline-block ml-2">{{ $author_model->get_author_name($course['author']) }}</span>
                                </div>
                                <div class="mt-lg-0 mt-3">
                                    <span class="meta_info mr-4">
                                        <a href="#"> <i class="ti-user mr-2"></i>25 </a>
                                    </span>
                                    <span class="meta_info"><a href="#"> <i class="ti-heart mr-2"></i>35 </a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--================ End Popular Courses Area =================-->

<!--================ Start Registration Area =================-->
<div class="section_gap registration_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="row clock_sec clockdiv" id="clockdiv">
                    <div class="col-lg-12">
                        <h1 class="mb-3">Register Now</h1>
                        <p>
                            There is a moment in the life of any aspiring astronomer that
                            it is time to buy that first telescope. It’s exciting to think
                            about setting up your own viewing station.
                        </p>
                    </div>
                    <div class="col clockinner1 clockinner">
                        <h1 class="days">150</h1>
                        <span class="smalltext">Days</span>
                    </div>
                    <div class="col clockinner clockinner1">
                        <h1 class="hours">23</h1>
                        <span class="smalltext">Hours</span>
                    </div>
                    <div class="col clockinner clockinner1">
                        <h1 class="minutes">47</h1>
                        <span class="smalltext">Mins</span>
                    </div>
                    <div class="col clockinner clockinner1">
                        <h1 class="seconds">59</h1>
                        <span class="smalltext">Secs</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1">
                <div class="register_form">
                    <h3>Courses for Free</h3>
                    <p>It is high time for learning</p>
                    <form class="form_area" id="myForm" action="mail.html" method="post">
                        <div class="row">
                            <div class="col-lg-12 form_group">
                                <input name="name" placeholder="Your Name" required="" type="text" />
                                <input name="name" placeholder="Your Phone Number" required="" type="tel" />
                                <input name="email" placeholder="Your Email Address" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" required="" type="email" />
                            </div>
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================ End Registration Area =================-->

<!--================ Start Trainers Area =================-->
<section class="trainer_area section_gap_top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="main_title">
                    <h2 class="mb-3">Our Expert Authors</h2>
                    <p>
                        Thanks for the authors that help our learning system grow up higher and higher!
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center d-flex align-items-center">
            @if (empty($authors))
            <div class="text-center">No author found</div>
            @else
            @foreach ($authors as $author)
            <div class="col-lg-3 col-md-6 col-sm-12 single-trainer">
                <div class="thumb d-flex justify-content-sm-center">
                    <img class="img-fluid" src="{{ empty($author['user_avatar_link']) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $author['user_avatar_link']) }}" alt="" />
                </div>
                <div class="meta-text text-sm-center">
                    <h4>{{ $author['user_first_name'] . ' ' . $author['user_last_name'] }}</h4>
                    <p class="designation">Author</p>
                    <div class="align-items-center justify-content-center d-flex">
                        <a href="#"><i class="ti-facebook"></i></a>
                        <a href="#"><i class="ti-twitter"></i></a>
                        <a href="#"><i class="ti-linkedin"></i></a>
                        <a href="#"><i class="ti-pinterest"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
<!--================ End Trainers Area =================-->

<!--================ Start Testimonial Area =================-->
<div class="testimonial_area section_gap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="main_title">
                    <h2 class="mb-3">Client say about me</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="testi_slider owl-carousel">
                <div class="testi_item">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <img src="{{ public_url('files/345879084_790341782305110_6487827050216876408_n.jpg') }}" alt="" />
                        </div>
                        <div class="col-lg-8">
                            <div class="testi_text">
                                <h4>Mr. Tuấn</h4>
                                <p>
                                    Tôi thực sự hài lòng với các khóa học về Trí tuệ nhân tạo và Học máy trên trang web học trực tuyến này. Các bài giảng chi tiết và dễ hiểu, giúp tôi nắm bắt được kiến thức một cách dễ dàng. Tôi cũng rất ấn tượng với giao diện thân thiện và dễ sử dụng. Tôi sẽ giới thiệu trang web này cho bạn bè và đồng nghiệp của mình!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testi_item">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <img src="{{ public_url('files/399254736_1722736261561071_4296959936071787565_n.jpg') }}" alt="" />
                        </div>
                        <div class="col-lg-8">
                            <div class="testi_text">
                                <h4>Mr. Nhâm Tùng</h4>
                                <p>
                                    我对这个在线学习网站上的人工智能和机器学习课程非常满意。讲座详细易懂，帮助我轻松掌握知识。用户友好且易于使用的界面也给我留下了深刻的印象。我会向我的朋友和同事推荐这个网站！
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testi_item">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <img src="{{ public_url('files/385542694_271433875385817_6017129006684811605_n.jpg') }}" alt="" />
                        </div>
                        <div class="col-lg-8">
                            <div class="testi_text">
                                <h4>Mr. Luyện</h4>
                                <p>
                                    เว็บไซต์การเรียนรู้ออนไลน์นี้เป็นแหล่งข้อมูลอันล้ำค่าสำหรับผู้ที่ต้องการเรียนรู้เกี่ยวกับปัญญาประดิษฐ์และการเรียนรู้ของเครื่อง หลักสูตรได้รับการออกแบบอย่างละเอียดและปรับปรุง ช่วยให้ฉันเข้าใจแนวคิดที่ซับซ้อนได้อย่างง่ายดาย ทีมสนับสนุนยินดีให้ความช่วยเหลือเสมอเมื่อฉันประสบปัญหา การเรียนรู้ไม่เคยสะดวกและสนุกขนาดนี้มาก่อน!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testi_item">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <img src="{{ public_url('files/a3lita.jpg') }}" alt="" />
                        </div>
                        <div class="col-lg-8">
                            <div class="testi_text">
                                <h4>Mr. Vinh</h4>
                                <p>
                                    Trang web học trực tuyến này là một nguồn tài nguyên vô giá cho những ai muốn tìm hiểu về Trí tuệ nhân tạo và Học máy. Các khóa học được thiết kế một cách chi tiết và cập nhật, giúp tôi nắm bắt được các khái niệm phức tạp một cách dễ dàng. Đội ngũ hỗ trợ luôn sẵn lòng giúp đỡ khi tôi gặp khó khăn. Học hỏi chưa bao giờ thuận tiện và thú vị đến thế!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================ End Testimonial Area =================-->