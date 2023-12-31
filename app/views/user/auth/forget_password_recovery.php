@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;
use app\core\Session;

if (!empty($data['error']))
$err = $data['error'];

@endphp

<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ assets('user/img/favicon.png') }}" type="image/png" />
    <title>{{ $data['page-title'] }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ assets('user/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/vendors/owl-carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/vendors/nice-select/css/nice-select.css') }}" />
    <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
    <link rel="stylesheet" href="{{ assets('user/css/auth.css') }}">
    <link rel="stylesheet" href="{{ assets('user/css/style.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/custom.css') }}">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="{{ assets('user/img/auth/frontImg.jpg') }}" alt="">
                <div class="text">
                    <span class="text-1">Every new friend is a <br> new adventure</span>
                    <span class="text-2">Let's get connected</span>
                </div>
            </div>
            <div class="back">
                <img class="backImg" src="{{ assets('user/img/auth/backImg.jpg') }}" alt="">
                <div class="text">
                    <span class="text-1">Complete miles of journey <br> with one step</span>
                    <span class="text-2">Let's get started</span>
                </div>
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="signup-form">
                    <div class="title">{{ $data['user_name'] }}, one more step to recover your account!</div>
                    <form method="post" action="{{ route_url('user.auth.forget_password_new') }}">
                        <div class="input-boxes">
                            @if (!empty($err))
                            <div class="alert alert-danger text-center" role="alert">{{ $err }}</div>
                            @endif
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="forget_password_new_pass" value="" placeholder="Enter your new password">
                            </div>
                            <div class="input-box">
                                <i class="fas fa-check"></i>
                                <input type="password" name="forget_password_new_pass_again" value="" placeholder="Re-enter your new password">
                            </div>
                            <div class="button input-box">
                                <input type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>