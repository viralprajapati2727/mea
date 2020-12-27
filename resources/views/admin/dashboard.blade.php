@extends('admin.app-admin')
@section('title') Dashboard @endsection
@section('page-header')
@php
    $user = Auth::user();
    $staff_dashboard_access = 1;
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
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

                        Why do we use it?
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                        
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
            <div class="col-sm-12">
                <h2>Users Statistics</h2>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.user.index') : 'javascript:void(0)' }}">{{ $users }}</a></h2><i class="flaticon-group text-indigo-400 counter-icon" style="left:49%; top:7px;"></i><div>Users</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.entrepreneur.index') : 'javascript:void(0)' }}">{{ $entrepreneurs }}</a></h2><i class="flaticon-entrepreneur text-pink-400 counter-icon" style="top: 5px; left: 49%;"></i><div>Entrepreneurs</div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-slider">
            <div class="col-sm-12">
                <h2>Jobs Statistics</h2>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.job.pending') : 'javascript:void(0)' }}">{{ $pendingjobs }}</a></h2><i class="flaticon-job-search text-indigo-400 counter-icon" style="left:49%; top:7px;"></i><div>Pending Jobs</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.job.active') : 'javascript:void(0)' }}">{{ $activejobs }}</a></h2><i class="flaticon-job-seeker text-pink-400 counter-icon" style="top: 5px; left: 49%;"></i><div>Active Jobs</div></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="hours-available-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); stroke: rgb(240, 98, 146);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.38342799370878,16.179613079472677L-32.57377388877674,15.328054496342538A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(240, 98, 146); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.job.archived') : 'javascript:void(0)' }}">{{ $closedjobs }}</a></h2><i class="flaticon-job-search-1 text-pink-400 counter-icon" style="top: 5px; left: 49%;"></i><div>Archived Jobs</div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="svg-center position-relative" id="goal-progress"><svg width="76" height="76"><g transform="translate(38,38)"><path class="d3-progress-background" d="M0,38A38,38 0 1,1 0,-38A38,38 0 1,1 0,38M0,36A36,36 0 1,0 0,-36A36,36 0 1,0 0,36Z" style="fill: rgb(238, 238, 238);"></path><path class="d3-progress-foreground" filter="url(#blur)" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); stroke: rgb(92, 107, 192);"></path><path class="d3-progress-front" d="M2.326828918379971e-15,-38A38,38 0 1,1 -34.3834279937087,-16.179613079472855L-32.573773888776664,-15.328054496342704A36,36 0 1,0 2.204364238465236e-15,-36Z" style="fill: rgb(92, 107, 192); fill-opacity: 1;"></path></g></svg><h2 class="pt-1 mt-2 mb-1"><a href="{{ ($user->type == 1) ? route('admin.job.pending') : 'javascript:void(0)' }}">{{ $rejectedjobs }}</a></h2><i class="flaticon-label text-indigo-400 counter-icon" style="left:49%; top:7px;"></i><div>Rejected Jobs</div></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap-datetimepicker.min.js') }}"></script>
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
   
</script>
@endsection
