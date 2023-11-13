@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;

$model = $data['model'];

$banner = empty($model['banner'] ?? $model['thumbnail']) ? 'assets/user/img/courses/course-enrollment-default.jpeg' : 'files/' . ($model['banner'] ?? $model['thumbnail']);

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Course Payment</h2>
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

<!--================ Start Course Enrollment Area =================-->
<section class="course_details_area section_gap">
    <div class="container">
        <div class="row">
            <!-- Course Information -->
            <div class="col-md-6">
                <div class="single_course">
                    <div class="course_head" style="display: flex; justify-content: center;">
                        <img class="img-fluid" src="{{ public_url($banner) }}" alt="" style="max-width: 100%; object-fit: fill; align-items: center;">
                    </div>
                    <div class="course_content">
                        <span class="price">{{ $model['price'] }}$</span>
                        <span class="tag mb-4 d-inline-block">{{ $model['subject'] }}</span>
                        <h4 class="mb-3">
                            <a href="{{ route_url('user.course.detail', ['id' => $model['id']]) }}">{{ $model['name'] }}</a>
                        </h4>
                        <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                            <div class="authr_meta">
                                <img src="{{ public_url('files/' . $model['author_avt'])}}" alt="" style="width: 45px; height: 45px; border-radius: 50%">
                                <span class="d-inline-block ml-2">{{ $model['author'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Payment Form -->
            <div class="col-md-6">
                <h2>Payment Details</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="cardName">Name on Card</label>
                        <input type="text" class="form-control" id="cardName" placeholder="LUU DINH LUYEN" />
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" />
                    </div>
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date</label>
                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" />
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" class="form-control" id="cvv" placeholder="CVV" />
                    </div>
                    <button type="submit" class="genric-btn primary ">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</section>
<!--================ End Course Enrollment Area =================-->