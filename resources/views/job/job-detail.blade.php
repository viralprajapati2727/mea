@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.job_status');
@endphp
<div class="page-main">
    <div class="page-wraper">
        <div class="job-top-details">
            <div class="container">
                <div class="job-inner-wrap">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="job-details-left">
                                <div class="row">
                                    {{--  <div class="col-md-2">
                                        <div class="job-profile">
                                            <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="" class="w-100">
                                        </div>
                                    </div>  --}}
                                    <div class="col-md-12">
                                        <div class="job-details-inner">
                                            <h2 class="job-title">{{ $job->job_title_id > 0 ? $job->jobTitle->title : $job->other_job_title }}</h2>
                                            <div class="job-company-location">
                                                <p class="company">{{ $job->job_type == 1 ? $job->category->title. " | " : ""  }} {{ Helper::timeAgo($job->created_at) }}</p>
                                            </div>
                                            <div class="d-sm-inline d-inline-block mr-3">
                                                <i class="fa fa-map-marker"></i>
                                                <span>{{ $job->location }}</span>
                                            </div>
                                            @if($job->job_type == 1)
                                            <div class="d-sm-inline d-inline-block mr-3">
                                                <i class="fa fa-clock-o"></i>
                                                <span>{{ config('constant.job_type')[$job->job_type_id] }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="job-details-right d-flex justify-content-end align-items-center">
                                <span class="job-status status-{{ strtolower($statuss[$job->job_status]) }} mr-2">{{ $statuss[$job->job_status] }}</span>
                                <a href="#" class="apply-btn">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="job-wrap job-description-wrap">
                    <h2 class="title">Job Description</h2>
                    <div class="text">
                        {!! $job->description !!}
                    </div>
                </div>
                <div class="job-wrap job-details">
                    <h2 class="title">Job Details</h2>
                    <div class="job-detail-list">
                        <label class="lable">View:</label>
                        <p>{{ $job->job_count }}</p>
                    </div>
                    @if($job->job_type == 1)
                    <div class="job-detail-list">
                        <label class="lable">Salary Range:</label>
                        <p>{{ $job->is_paid ? ($job->currency->code ." ". $job->min_salary." - ".$job->currency->code ." ". $job->max_salary) : "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Working Time:</label>
                        <p>{{ $job->job_start_time ." to ". $job->job_end_time }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Timezone:</label>
                        <p>{{ $job->time_zone }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Skills:</label>
                        <ul class="skills">
                            @foreach($job->key_skills as $skill)
                            <li>{{ isset($skill) ? $skill : "" }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="job-detail-list">
                        <label class="lable">Find Team Member?:</label>
                        <p>{{ $job->is_find_team_member == 1 ? "Yes" : "No" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Find Team Content:</label>
                        <p>{{ $job->find_team_member_text}}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 