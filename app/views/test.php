@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Course Name</h2>
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
                    <img class="img-fluid" src="{{ public_url('files/FREE-Python-Course-For-Beginners.png') }}" alt="">
                </div>
                <div class="content_wrapper">
                    <h4 class="title">Description</h4>
                    <div class="content">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni laudantium minus incidunt minima? Praesentium id provident nesciunt, repellat, esse sint molestiae pariatur, doloribus velit voluptates magnam rem quo corporis ex.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste omnis vel minus iure eligendi suscipit at voluptates. Quod corporis cum officiis quisquam delectus totam libero nisi! Minima molestias voluptatibus sequi!
                    </div>
                    <h4 class="title">Course Lessons</h4>
                    <div class="content">
                        <ul class="course_list">
                            <li class="d-flex" style="flex-direction: column; ">
                                <a class="justify-content-between d-flex" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <span class="circle">1</span>
                                    <span>Lorem ipsum dolor sit amet.</span>
                                    <i class="fas fa-times"></i>
                                </a>
                                <div class="collapse m-3" id="collapseExample">
                                    <ul>
                                        <li>
                                            <a class="justify-content-between d-flex" href="#">
                                                <span>Lorem ipsum dolor sit amet.</span>
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <hr/>
                                        </li>
                                        <li>
                                            <a class="justify-content-between d-flex" href="#">
                                                <span>Lorem ipsum dolor sit amet.</span>
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <hr/>
                                        </li>
                                        <li>
                                            <a class="justify-content-between d-flex" href="#">
                                                <span>Lorem ipsum dolor sit amet.</span>
                                                <i class="fas fa-times"></i>
                                            </a>
                                            <hr/>
                                        </li>
                                    </ul>
                                </div>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 right-contents">
                <ul>
                    <h3>Pick up where you left off</h3>
                    <!-- <br> -->
                    <a href="{{ '#' }}" class="genric-btn info p-1 text-uppercase enroll rounded-0 text-white">Start learning</a>
                </ul>
                <ul>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div> Belong To Subject </div>
                            <div> A3lita </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div>Last Update Date </div>
                        </div>
                    </li>
                    <li>
                        <div class="justify-content-between d-flex">
                            <div> Belong To Subject </div>
                        </div>
                    </li>
                    <li>
                        <div>Related Course</div>
                        <ul>
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, corporis!</a></li>
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, corporis!</a></li>
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, corporis!</a></li>
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, corporis!</a></li>
                        </ul>
                    </li>
                </ul>


            </div>
        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->