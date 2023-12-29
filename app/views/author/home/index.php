@php
use function app\core\helper\assets;
use function app\core\helper\route_url;
use function app\core\helper\public_url;
use function app\core\helper\str_date;
use function app\core\helper\add_pad;
use function app\core\helper\precent;


$total_income = $data['total_income'];
$today_income = $data['today_income'];
$total_learners = $data['total_learners'];
$today_learners = $data['today_learners'];
$total_courses = $data['total_courses'];
$enrolled_courses = $data['enrolled_courses'];
$completed_courses = $data['completed_courses'];

@endphp
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Dashboard</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_url('author.home.index') }}">Dashboard</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <h4 class="card-title">Today Income </h4>
                            <div class="stat-digit"> <i class="fa fa-usd"></i>{{ $today_income }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success w-{{ $total_income != 0 ? intdiv($today_income * 100, $total_income) : '0' }}" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <h4 class="card-title">Total Income</h4>
                            <div class="stat-digit"> <i class="fa fa-usd"></i>{{ $total_income  }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary w-{{ $total_income == 0 ? '0' : '100' }}" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <h4 class="card-title">Today Learners</h4>
                            <div class="stat-digit"> <i class="fa fa-user"></i>{{ add_pad($today_learners) }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning w-{{ $today_learners != 0 ? intdiv($today_learners * 100, $total_learners) : '0' }}" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <h4 class="card-title">Total Learners</h4>
                            <div class="stat-digit"> <i class="fa fa-user"></i>{{ add_pad($total_learners) }}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger w-{{ $total_learners == 0? '0' : '100' }}" role="progressbar"></div>
                        </div>
                    </div>
                </div>
                <!-- /# card -->
            </div>
            <!-- /# column -->
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="m-t-10 col-lg-12 ">
                                <h4 class="card-title mt-3">Total Courses</h4>
                                <h1 class="mt-3 mb-3">{{ add_pad($total_courses) }}</h1>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <h4 class="card-title">Enrolled</h4>
                                <h2 class="mt-3">{{ add_pad($enrolled_courses) }}</h2>
                                <div class="mt-3 current-progressbar">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary w-{{ round(precent($enrolled_courses, $total_courses)) }} pt-2 pb-2" role="progressbar">
                                            {{ round(precent($enrolled_courses, $total_courses)) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="card-title">Completed</h4>
                                <h2 class="mt-3">{{ add_pad($completed_courses) }}</h2>
                                <div class="mt-3 current-progressbar">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success w-{{ round(precent($completed_courses, $total_courses), 10) }} pt-2 pb-2" role="progressbar">
                                            {{ round(precent($completed_courses, $total_courses)) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sales Overview</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-8">
                                <div id="morris-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>