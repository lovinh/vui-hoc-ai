@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
@endphp

<div class="nav-header">
    <a href="{{ route_url('author.home.index') }}" class="brand-logo">
        <img class="logo-abbr" src="{{ assets('common/imgs/logo.png') }}" alt="">
        <h2 class="brand-title text-white" style="font-size: 20px; margin-left: 20px; margin-top: 10px; padding: 0px">Author Studio</h2>
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>