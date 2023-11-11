@php
use function app\core\helper\assets;
use function app\core\helper\url;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use app\core\view\View;
use app\core\Session;

$subjects = View::get_data_share('subject');

$user = View::get_data_share('user_name');

$user_avt = View::get_data_share('user_avt');

@endphp

<header class="header_area">
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
                <a class="navbar-brand logo_h" href="index.html"><img src="{{ assets('user/img/logo.png') }}" alt="" /></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span> <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route_url('user.course.index') }}">Course</a>
                        </li>
                        <li class="nav-item submenu dropdown">
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
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-columns p-2"></i>
                                        Your dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
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