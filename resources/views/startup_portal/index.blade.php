@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.job_status');
@endphp
<div class="my-jobs">
    <div class="container">
        <div class="page-header page-header-light">
            <div class="header-elements-md-inline job-list-header my-job-list-header">
                <div class="page-title d-flex p-0">
                    <h2 class="font-color page-title">Startup Portal</h2>
                </div>
                <div class="job-header-elements d-sm-flex">
                    <a href="{{ route('start-statup-portal',['action'=>'create']) }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn"><i class="flaticon-business-idea"></i>Create new Startup </a>
                </div>
            </div>
        </div>
        <!-- Content area -->
        <div class="">
            @if(!$startups->isEmpty())
                <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
                    <div class="row">
                        <div class="col-3">
                            <h5 class="font-black text-left">Startup Name</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Startup industry</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Startup location</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Stage of startup</h5>
                        </div>
                        <div class="col-1 text-center">
                            <h5 class="font-black">Startup Status</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black text-center">Action</h5>
                        </div>
                    </div>
                </div>
                @forelse ($startups as $startup)
                    <div class="col jb_border_bottm_gray job-item">
                        <div class="row">
                            <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                <div class="jb_company_myjob_title">
                                    <h4 class="font-weight-semibold">
                                        {{-- <a href="{{ route('job.job-detail',['id' => $job->job_unique_id]) }}" class="font-black"> --}}
                                            {{ $startup->name != null ? $startup->name : "" }}
                                        {{-- </a> --}}
                                    </h4>
                                    <div class="text-muted jb_my_job_company_bottom_location">
                                        <div class="d-block job-address">
                                            <i class="flaticon-pin mr-1"></i>
                                            {{ $startup->location }}
                                        </div>
                                        {{-- @if($job->job_type == 1)
                                        <div class="d-block">
                                            <i class="flaticon-wall-clock mr-1"></i>{{ config('constant.job_type')[$job->job_type_id] }}
                                        </div>
                                        @endif --}}
                                    </div>
                                </div>
                                <!-- mobile only -->
                                {{-- <div class="d-block d-lg-none main-dropdown text-right">
                                    <div class="d-inline-block mr-1 mr-sm-2 main-status"><span class="status"></span></div>
                                    <div class="d-inline-block">
                                        <div class="list-icons">
                                            <div class="list-icons-item dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="flaticon-menu"></i></a>
                                                <span class="tooltip-arrow"></span>
                                                <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav">
                                                    <a href="{{ route('job.fill-job',['job_unique_id' => $job->job_unique_id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit Job</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block"></div>
                                </div> --}}
                                <!--end mobile only -->
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class=""><span class="d-inline-block d-lg-none"><b>Startup industry : </b>&nbsp;</span>{{ $startup->industry }}</div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class=""><span class="d-inline-block d-lg-none"><b> </b>&nbsp;</span>{{ $startup->location }}</div>
                            </div>
                            <div class="col-lg-2 col-12 main-duration">
                                <div class="">{{ config('constant.stage_of_startup')[$startup->stage_of_startup] }}</div>
                            </div>
                            <div class="col-lg-1 col-12 d-none d-lg-block main-status">
                                <div class=""><span class="status-{{ strtolower($statuss[$startup->status]) }}">{{ config('constant.job_status')[$startup->status] }}</span></div>
                            </div>
                            <!-- desktop only -->
                            <div class="col-lg-2 col-12 d-none d-lg-block main-dropdown">
                                <div class="text-center">
                                    <div class="list-icons">
                                        <div class="list-icons-item dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="flaticon-menu"></i></a>
                                            <span class="tooltip-arrow"></span>
                                            <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(30px, 30px, 0px);">
                                                <a href="{{ route('start-statup-portal',['action'=>'view','portal_id' => $startup->id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-user"></i></span>View Startup Portal Details</a>
                                                <a href="{{ route('start-statup-portal',['action'=>'create','portal_id' => $startup->id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit Portal</a>
                                                <a href="javascript:;" class="dropdown-item call-action" data-id="143" data-status="delete"><span class="main-icon-span"><i class="flaticon-trash"></i></span> Delete Portal</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end desktop only -->
                        </div>
                    </div>
                @empty
                @endforelse
                <div class="pagination my-5">
                    {{ $startups->onEachSide(1)->links() }}
                </div>
            @else
            <div class="pagination my-5">No Startups Found!!</div>
            @endif
        </div>
    </div>
</div>
@endsection