@php
use function app\core\helper\route_url;
@endphp


<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Dashboard</li>
            <li><a href="#" aria-expanded="false"><i class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
            </li>
            <li class="nav-label">Course</li>
            <li><a class="has-arrow" href="{{ route_url('author.course.index') }}" aria-expanded="false"><i class="icon icon-app-store"></i><span class="nav-text">Course List</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route_url('author.course.index') }}">All Courses</a></li>
                    <li><a href="{{ route_url('author.course.available') }}">Available Courses</a></li>
                    <li><a href="{{ route_url('author.course.draft') }}" aria-expanded="false">Draft Courses</a></li>
                </ul>
            </li>
            <li><a href="{{ route_url('author.course.new') }}" aria-expanded="false"><i class="icon icon-window-add"></i><span class="nav-text">Create Course</span></a></li>
            </li>
            <li class="nav-label">Messages</li>
            <li><a href="javascript:void()" aria-expanded="false"><i class="icon icon-world-2"></i><span class="nav-text">System Messages</span></a>
            <li><a href="javascript:void()" aria-expanded="false"><i class="icon icon-email-84"></i><span class="nav-text">User messages</span></a>
            </li>
            <li><a href="javascript:void()" aria-expanded="false"><i class="icon icon-check-2"></i><span class="nav-text">Read messages</span></a>
            </li>

            <li class="nav-label">Menu</li>
            <li><a href="javascript:void()" aria-expanded="false"><i class="icon icon-form"></i><span class="nav-text">Back to user</span></a> </li>

        </ul>
    </div>


</div>