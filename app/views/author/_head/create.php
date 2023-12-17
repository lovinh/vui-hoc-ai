@php
use function app\core\helper\assets;
@endphp
<link href="{{ assets('author/vendor/jquery-steps/css/jquery.steps.css') }}" rel="stylesheet">
<style>
    .wizard>.content {
        background-color: #fff;
    }

    .ck-editor__editable_inline {
        min-height: 300px;
    }
    .ck-label {
        display: none !important;
    }
</style>