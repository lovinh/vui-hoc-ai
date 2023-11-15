@php
use function app\core\helper\assets;
use function app\core\helper\url;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use app\core\view\View;
use app\core\Session;

$subjects = View::get_data_share('subject');

$user = View::get_data_share('user_name');

$user_id = View::get_data_share('user_id');

$user_avt = View::get_data_share('user_avt');

$user_status = View::get_data_share('user_status');


@endphp

<header class="header_area {{ empty($data['home']) ? 'white-header' : false }}">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a href="{{ route_url('user.learning.intro', ['id' => $data['model']['course_id']]) }}">
                    <h3 class="text-white m-3" style="margin-bottom: 0px; max-width: 450px;">{{ $data['model']['course_name'] }}</h3>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span> <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route_url('user.learning.intro', ['id' => $data['model']['course_id']]) }}">Course</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route_url('user.learning.progress', ['id' =>  $data['model']['course_id']]) }}">Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about-us.html">Note</a>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="/">Discussion</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/">FAQ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/">Resource</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/">Related Course</a>
                                </li>
                            </ul>
                        </li>
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
                    </ul>
                </div>
            </div>
        </nav>
    </div>

</header>