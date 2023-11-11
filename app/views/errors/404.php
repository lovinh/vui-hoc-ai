@php

use function app\core\helper\url;

@endphp


<section class="home_banner_area">
    <div class="banner_inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner_content text-center">

                        <h2 class="text-uppercase mt-4 mb-5">
                            404 Page Not Found!
                        </h2>

                        <p class="text-uppercase">
                            Could not found any page that matchs your request
                        </p>

                        <div>
                            <a href="{{ url('') }}" class="primary-btn2 mb-3 mb-sm-0">Go back to home page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>