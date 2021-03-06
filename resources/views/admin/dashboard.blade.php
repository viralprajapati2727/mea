@extends('admin.app-admin')
@section('title') Dashboard @endsection
@section('page-header')
@php
    $user = Auth::user();
    $staff_dashboard_access = 0;
@endphp
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection


@section('content')
    @if($staff_dashboard_access == 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 mx-auto">
                    <div class="logo text-center">
                        <img src="{{ Helper::assets('images/logo.png') }}" class="" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 mx-auto text-justify">
                    <p>
                        Welcome to <strong>Dancero</strong>, your number one source for dancing. A market place for dancing classes and events, on addition to social media of dancing. We're dedicated to giving you the very best of our services, with a focus on connecting dancers together, search for dance places, and win dancing challenges.
                    </p><br>
                    <p>
                        Founded in 2019 by Harmony Academy, <strong>Dancero</strong> has come a long way from its beginnings in 12th floor, Sharqia Tower, Jaber Al-Mubarak Street, Block 2, Sharq, Kuwait City, and Kuwait. Post code: 15300. When Harmony Academy first started out, his passion for training dance online, drove him create this website so that <strong>Dancero</strong> can offer you the world's best dancing website. We now serve customers all over the world, and are thrilled that we're able to turn our passion into our own website.
                    </p><br>
                    <p>
                        We hope you enjoy our services as much as we enjoy offering them to you.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <h5 class="card-title">Statistics</h5>
                    </div>
                    <div class="col-12 col-lg-9 p-0">
                        <form id="dashbord-form" class="d-flex">
                            <div class="col">
                                <input type="text" name="from_date" value="" placeholder="From Date" id="from_date" class="form-control datetimepicker" autocomplete="off">
                                <div class="subscribe-send-icon">
                                    <i class="flaticon-calendar"></i>
                                </div>
                            </div>
                            <div class="col">
                                <input type="text" name="to_date" value="" placeholder="To Date" id="to_date" class="form-control datetimepicker" autocomplete="off">
                                <div class="subscribe-send-icon">
                                    <i class="flaticon-calendar"></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="text" type="submit" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                                <button type="text" type="reset" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-slider">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.index') : 'javascript:void(0)' }}">{{ $professional }}</a></h2><i class="flaticon-man text-indigo-400 counter-icon" style="left:48.5%; top:7px;"></i><div>Professionals</div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.index') : 'javascript:void(0)' }}">{{ $dancers }}</a></h2><i class="flaticon-null text-pink-400 counter-icon dancerIcon" style="top: 5px; left: 46%;"></i><div>Dancers</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.index') : 'javascript:void(0);' }}">{{ $event }}</a></h2><i class="flaticon-election-event-on-a-calendar-with-star-symbol text-pink-400 counter-icon" style="left:49%; top:7px;"></i><div>Events Registered</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="javascript:void(0);"></a></h2><i class="flaticon-calendar-1 text-indigo-400 counter-icon" style="left: 49%; top: 7px;"></i><div>Bookings Made</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="javascript:void(0);">{{ config('constant.USD')." "}}</a></h2><i class="flaticon-economic text-pink-400 counter-icon" style="left:48%; top:7px;"></i><div>Revenue Generated</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="javascript:void(0);">{{ config('constant.USD')." "}}</a></h2><i class="flaticon-cost text-indigo-400 counter-icon" style="left:49%; top:7px;"></i><div>Platform Earnings</div></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('footer_content')
<script src="{{ Helper::assets('js/owlcarousel/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap-datetimepicker.min.js') }}"></script>
<link href="{{ Helper::assets('css/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Helper::assets('css/owlcarousel/owl.theme.default.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript">
    $(document).ready(function(){
        $(".datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: 'DD/MM/YYYY',
            useCurrent:false,
            maxDate: moment().endOf('d')
        });

        $(document).on('click',"#btnReset",function(e){
            e.preventDefault();
            $("input").val('');
            $("#dashbord-form").submit();
        });

        $('#from_date').on("dp.change", function (e) {
            var d = new Date(e.date);
            $('#to_date').data("DateTimePicker").minDate(d);
        });
        $('#to_date').on("dp.change", function (e) {
            var d = new Date(e.date);
            $('#from_date').data("DateTimePicker").maxDate(d);
        });
    });
</script>
<script>
    jQuery(document).ready(function($) {
"use strict";
 $('#categories-slider').owlCarousel( {
		loop: true,
		center: true,
		items: 3,
		margin: 30,
		autoplay: true,
		dots:true,
        nav:true,
		autoplayTimeout: 8500,
		smartSpeed: 450,
  	    navText: ['<i class="flaticon-arrow-3"></i>','<i class="flaticon-arrow-1"></i>'],
		responsive: {
			0: {
				items: 1
			},
			768: {
				items: 2
			},
			1170: {
				items: 3
			}
		}
	});
});
</script>
@endsection
