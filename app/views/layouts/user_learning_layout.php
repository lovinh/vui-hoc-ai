@php
use function app\core\helper\assets;
use function app\core\helper\view_path;
use function app\core\helper\render_block;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="{{ assets('user/img/favicon.png') }}" type="image/png" />
    <title>{{$data['page-title']}}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ assets('user/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/vendors/owl-carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/vendors/nice-select/css/nice-select.css') }}" />

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- main css -->
    <link rel="stylesheet" href="{{ assets('user/css/style.css') }}" />
    <link rel="stylesheet" href="{{ assets('user/css/custom.css') }}">
</head>

<body>
    <!--================ Start Header Menu Area =================-->
    @php
    render_block('blocks/user/header_learning', $data);
    @endphp
    <!--================ End Header Menu Area =================-->

    @php
    render_block($data['view'], $data);
    @endphp

    <!--================ Start footer Area  =================-->
    @php
    render_block('blocks/user/footer', $data);
    @endphp
    <!--================ End footer Area  =================-->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ assets('user/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ assets('user/js/popper.js') }}"></script>
    <script src="{{ assets('user/js/bootstrap.min.js') }}"></script>
    <script src="{{ assets('user/vendors/nice-select/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ assets('user/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ assets('user/js/owl-carousel-thumb.min.js') }}"></script>
    <script src="{{ assets('user/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ assets('user/js/mail-script.js') }}"></script>
    <!--gmaps Js-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
    <script src="{{ assets('user/js/gmaps.min.js') }}"></script>
    <script src="{{ assets('user/js/theme.js') }}"></script>
</body>

</html>