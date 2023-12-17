@php
use function app\core\helper\assets;
@endphp
<!-- Vectormap -->
<script src="{{ assets('author/vendor/raphael/raphael.min.js') }}"></script>
<script src="{{ assets('author/vendor/morris/morris.min.js') }}"></script>


<script src="{{ assets('author/vendor/circle-progress/circle-progress.min.js') }}"></script>
<script src="{{ assets('author/vendor/chart.js/Chart.bundle.min.js') }}"></script>

<script src="{{ assets('author/vendor/gaugeJS/dist/gauge.min.js') }}"></script>

<!--  flot-chart js -->
<script src="{{ assets('author/vendor/flot/jquery.flot.js') }}"></script>
<script src="{{ assets('author/vendor/flot/jquery.flot.resize.js') }}"></script>

<!-- Owl Carousel -->
<script src="{{ assets('author/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>

<!-- Counter Up -->
<script src="{{ assets('author/vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
<script src="{{ assets('author/vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
<script src="{{ assets('author/vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>


<script src="{{ assets('author/js/dashboard/dashboard-1.js') }}"></script>

<!-- ckeditor -->
<script src="{{ assets('user/vendors/ckeditor/build/ckeditor.js') }}"></script>
<script src="{{ assets('author/js/script.js') }}"></script>

<script>
    document.querySelectorAll("a[href='#finish']")[0].onclick = function() {
        document.getElementById("step-form-horizontal").submit();
    }
</script>