@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\view_path;
use function app\core\helper\render_block;

@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vui Hoc AI - Author Dashboard - {{ $data['title'] }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ assets('common/imgs/logo.png') }}">
    @php
    render_block('author/_common/index', $data);
    @endphp
    @php
    render_block($data['head'], $data);
    @endphp

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    @php
    render_block('blocks/author/preload', $data);
    @endphp
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        @php
        render_block('blocks/author/nav_header', $data)
        @endphp
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        @php
        render_block('blocks/author/header', $data);
        @endphp
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @php
        render_block('blocks/author/sidebar', $data);
        @endphp
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        @php
        render_block($data['page'], $data);
        @endphp
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        @php
        render_block('blocks/author/footer', $data);
        @endphp
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    
    @php
    render_block($data['script'], $data);
    @endphp
    
    @php
    render_block('author/_common/script', $data);
    @endphp

</body>

</html>