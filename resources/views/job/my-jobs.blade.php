@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.job_status');
@endphp
<div class="my-jobs">
    <div class="page-main">
        <div class="container">
            <div class="page-header page-header-light">
                <div class="header-elements-md-inline job-list-header my-job-list-header">
                    <div class="page-title d-flex p-0">
                        <h2 class="font-color page-title">Jobs</h2>
                    </div>
                    <div class="job-header-elements d-sm-flex">
                        <a href="{{ route('job.fill-job') }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn">Post a Job</a>
                    </div>
                </div>
            </div>
            <!-- Content area -->
            <div class="">
                @if(!$jobs->isEmpty())
                    <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
                        <div class="row">
                            <div class="col-3">
                                <h5 class="font-black text-left">Job Title</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Type Of Job</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Job ID</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Started</h5>
                            </div>
                            <div class="col-1 text-center">
                                <h5 class="font-black">Job Status</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black text-center">Action</h5>
                            </div>
                        </div>
                    </div>
                    @forelse ($jobs as $job)
                        <div class="col jb_border_bottm_gray job-item">
                            <div class="row">
                                <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                    <div class="jb_company_myjob_title">
                                        <h4 class="font-weight-semibold">
                                            <a href="{{ route('job.job-detail',['id' => $job->job_unique_id]) }}" class="font-black">{{ $job->job_title_id > 0 ? $job->jobTitle->title : $job->other_job_title }}</a>
                                        </h4>
                                        <div class="text-muted jb_my_job_company_bottom_location">
                                            <div class="d-block job-address">
                                                <i class="flaticon-pin mr-1"></i>
                                                {{ $job->location }}
                                            </div>
                                            @if($job->job_type == 1)
                                            <div class="d-block">
                                                <i class="flaticon-wall-clock mr-1"></i>{{ config('constant.job_type')[$job->job_type_id] }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- mobile only -->
                                    <div class="d-block d-lg-none main-dropdown text-right">
                                        <div class="d-inline-block mr-1 mr-sm-2 main-status"><span class="status-{{ strtolower(config('constant.job_status')[$job->job_status]) }}">{{ config('constant.job_status')[$job->job_status] }}</span></div>
                                        <div class="d-inline-block">
                                            <div class="list-icons">
                                                <div class="list-icons-item dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="flaticon-menu"></i></a>
                                                    <span class="tooltip-arrow"></span>
                                                    <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav">
                                                        {{-- <a href="https://staging.jobaroot.com/view-applicant/J000143" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-user"></i></span>View Applicants</a> --}}
                                                        <a href="{{ route('job.fill-job',['job_unique_id' => $job->job_unique_id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit Job</a>
                                                        {{-- <a href="javascript:;" class="dropdown-item call-action" data-id="143" data-status="delete"><span class="main-icon-span"><i class="flaticon-trash"></i></span> Delete Job</a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block"></div>
                                    </div>
                                    <!--end mobile only -->
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Type Of Job : </b>&nbsp;</span>{{ $job->job_type == 1 ? 'Post Job' : 'Post Request' }}</div>
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Job ID : </b>&nbsp;</span>{{ $job->job_unique_id }}</div>
                                </div>
                                <div class="col-lg-2 col-12 main-duration">
                                    <div class="">{{ Helper::timeAgo($job->created_at) }}</div>
                                </div>
                                <div class="col-lg-1 col-12 d-none d-lg-block main-status">
                                    <div class=""><span class="status-{{ strtolower($statuss[$job->job_status]) }}">{{ config('constant.job_status')[$job->job_status] }}</span></div>
                                </div>
                                <!-- desktop only -->
                                <div class="col-lg-2 col-12 d-none d-lg-block main-dropdown">
                                    <div class="text-center">
                                        <div class="list-icons">
                                            <div class="list-icons-item dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="flaticon-menu"></i></a>
                                                <span class="tooltip-arrow"></span>
                                                <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(30px, 30px, 0px);">
                                                    <a href="{{ route('job.view-applicant',['job_unique_id' => $job->job_unique_id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-user"></i></span>View Applicants</a>
                                                    <a href="{{ route('job.fill-job',['job_unique_id' => $job->job_unique_id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit Job</a>
                                                    {{-- <a href="javascript:;" class="dropdown-item call-action" data-id="143" data-status="delete"><span class="main-icon-span"><i class="flaticon-trash"></i></span> Delete Job</a> --}}
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
                        {{ $jobs->onEachSide(1)->links() }}
                    </div>
                @else
                <div class="pagination my-5">No Jobs Found!!</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection