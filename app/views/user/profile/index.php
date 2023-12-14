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
                        <h2>Your profile</h2>
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
        <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            <img src="{{ empty($model['user_avatar']) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $model['user_avatar']) }}" alt="" />
                            <div class="file btn btn-lg btn-primary">
                                Change Photo
                                <input type="file" name="file" />
                            </div>
                        </div>
                        <div class="profile-work">
                            <a href="#" class="genric-btn info-border">Edit profile</a>
                            <a href="#" class="genric-btn info-border">Change password</a>
                            @if ($model['user_role'] == 'user' && $model['user_role'] == 'learner')
                            <a href="#" class="genric-btn primary">Become an author</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                            <div class="profile-head-info">
                                <h1>
                                    {{ $model['user_name'] }}
                                </h1>
                                <h3 style="text-transform: capitalize;">
                                    {{ $model['user_role'] }}
                                </h3>
                            </div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Information</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>First name</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $model['user_first_name'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Last name</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $model['user_last_name'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $model['user_email'] }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $model['user_status'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                @if (empty($model['user_information']))
                                <div class="text-center">No Information</div>
                                @else
                                @foreach ($model['user_information'] as $info)
                                <div class="card profile-info-item">
                                    <h3 class="card-header">{{ $info['info_type'] }}</h3>
                                    <div class="card-body profile-info-item-body">
                                        {! $info['info_description'] !}
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--================ End All Courses Area ===================-->