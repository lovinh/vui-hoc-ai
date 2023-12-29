@php
use function app\core\helper\assets;
use function app\core\helper\url;
@endphp
<script src="{{ assets('author/vendor/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ assets('author/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<!-- Form validate init -->
<script src="{{ assets('author/js/plugins-init/jquery.validate-init.js') }}"></script>

<!-- Form step init -->
<script src="{{ assets('author/js/plugins-init/jquery-steps-init.js') }}"></script>


<script>
    document.querySelectorAll("a[href='#finish']")[0].onclick = function() {
        document.getElementById("step-form-horizontal").submit();
    }
</script>