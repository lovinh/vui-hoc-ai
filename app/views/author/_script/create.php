@php
use function app\core\helper\assets;
@endphp
<script src="{{ assets('author/vendor/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ assets('author/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<!-- Form validate init -->
<script src="{{ assets('author/js/plugins-init/jquery.validate-init.js') }}"></script>

<!-- Form step init -->
<script src="{{ assets('author/js/plugins-init/jquery-steps-init.js') }}"></script>

<!-- ckeditor -->
<script src="{{ assets('user/vendors/ckeditor/build/ckeditor.js') }}"></script>
<script src="{{ assets('author/js/script.js') }}"></script>


<script>
    document.querySelectorAll("a[href='#finish']")[0].onclick = function() {
        document.getElementById("step-form-horizontal").submit();
    }
</script>