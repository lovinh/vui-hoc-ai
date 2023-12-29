@php
use function app\core\helper\assets;
use function app\core\helper\url;
@endphp
<link href="{{ assets('author/css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ url('/vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ assets('author/vendor/toastr/css/toastr.min.css') }}">