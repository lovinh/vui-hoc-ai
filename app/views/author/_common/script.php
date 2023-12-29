@php
use function app\core\helper\assets;
use function app\core\helper\url;

@endphp
<script src="{{ assets('author/vendor/global/global.min.js') }}"></script>
<script src="{{ assets('author/js/quixnav-init.js') }}"></script>
<script src="{{ assets('author/js/custom.min.js') }}"></script>

<!-- ckeditor -->
<script src="{{ url('vendor/ckeditor/ckeditor.js') }}"></script>

<!-- toastr -->
<script src="{{ assets('author/vendor/toastr/js/toastr.min.js') }}"></script>

<!-- Select 2 -->
<script src="{{ url('/vendor/select2/js/select2.min.js') }}"></script>

<!-- User defined script -->
<script src="{{ assets('author/js/script.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>

@if (!empty($data['response']))
@if ($data['response']['status'])
<script>
    toastr.success("{{ $data['response']['message'] }}", "Success", {
        timeOut: 500000000,
        closeButton: !0,
        debug: !1,
        newestOnTop: !0,
        progressBar: !0,
        positionClass: "toast-top-right",
        preventDuplicates: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: !1
    })
</script>
@else
<script>
    toastr.error("{{ $data['response']['message'] }}", "Error", {
        positionClass: "toast-top-right",
        timeOut: 5e3,
        closeButton: !0,
        debug: !1,
        newestOnTop: !0,
        progressBar: !0,
        preventDuplicates: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: !1
    })
</script>
@endif
@endif