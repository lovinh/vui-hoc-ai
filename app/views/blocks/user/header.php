@php
use function app\core\helper\assets;
use function app\core\helper\url;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use app\core\view\View;
use app\core\Session;

$subjects = View::get_data_share('subject');

$user_id = View::get_data_share('user_id');

$user = View::get_data_share('user_name');

$user_avt = View::get_data_share('user_avt');

$user_status = View::get_data_share('user_status');


@endphp
@if (strtoupper($user_status) == strtoupper("deactive"))
<div class="alert alert-warning" style="margin-bottom: 0 !important;">Your account is set to <b>deactive</b> because you haven't validate your email before! Click <a href="{{ route_url('user.auth.validate_email') }}">here</a> to validate your email and active your account!</div>
@endif
<header class="header_area {{ empty($data['home']) ? 'white-header' : false }}">
    <div class="main_menu">
        <div class="search_input" id="search_input_box">
            <div class="container">
                <form class="d-flex justify-content-between" method="get" action="{{ route_url('user.course.search_name') }}">
                    <input type="text" class="form-control" name="search_input" id="search_input" placeholder="Search Here" />
                    <button type="submit" class="btn"></button>
                    <span class="ti-close" id="close_search" title="Close Search"></span>
                </form>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="{{ route_url('user.home.index') }}"><img src="{{ assets('user/img/logo-3.png') }}" alt="" width="45px"/><h1 class="{{ $data['page'] == 'home' ?  false : 'text-white'}}" style="display: inline; vertical-align: middle; margin-left: 10px;">Vui Hoc AI</h1></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span> <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item {{ $data['page'] == 'home' ? 'active' : false }}">
                            <a class="nav-link" href="{{ url('') }}">Home</a>
                        </li>
                        <li class="nav-item {{ $data['page'] == 'course' ? 'active' : false }}">
                            <a class="nav-link" href="{{ route_url('user.course.index') }}">Course</a>
                        </li>
                        <li class="nav-item submenu dropdown {{ $data['page'] == 'subject' ? 'active' : false }}">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Subjects</a>
                            <ul class="dropdown-menu">
                                @if (!empty($subjects))
                                @foreach ($subjects as $name)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route_url('user.course.search_subject', ['subject' => $name['subject_name']]) }}">{{ $name['subject_name'] }}</a>
                                </li>
                                @endforeach
                                @else
                                <li class="nav-item">
                                    <p class="nav-link">Subject not found</p>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="/">Explore more...</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about-us.html">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link search" id="search">
                                <i class="ti-search"></i>
                            </a>
                        </li>

                        @if (empty($user))
                        <li class="nav-item">
                            <a href="{{ route_url('user.auth.index') }}" class="nav-link genric-btn primary" style="padding: 0px 20px">
                                Join now
                            </a>
                        </li>
                        @else
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ empty($user_avt) ? assets('user/img/default-user-avt.jpg') : public_url('files/' . $user_avt ) }}" alt="" style="width: 25px; height: 25px; border-radius: 50%" />
                                <span class="p-3 text-center">{{ $user }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route_url('user.dashboard.index', ['id' => $user_id]) }}">
                                        <i class="fas fa-columns p-2"></i>
                                        Your dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route_url('user.profile.index', ['id' => $user_id]) }}">
                                        <i class="far fa-id-card p-2"></i>
                                        Your profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-exclamation-circle p-2"></i>
                                        Your message
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route_url('user.auth.sign_out') }}">
                                        <i class="fas fa-sign-out-alt p-2"></i>
                                        Sign out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>