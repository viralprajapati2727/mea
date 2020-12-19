@extends('admin.app-admin')
@section('title') Job Management @endsection
@section('page-header')
    @php
        $statuss = config('constant.job_status');
    @endphp
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('admin.job.'.$status) }}" class="breadcrumb-item">{{ ucfirst($status) }} Job</a>
                    <span class="breadcrumb-item active">Job Detail</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <h6 class="card-title text-center">Job Details</h6>
    <div class="card">
        <div class="card-body custom-tabs">
            <div class="row">
                <div class="col-md-8">
                    <div class="detail-section">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job ID</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_unique_id }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Title</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->jobTitle->title }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Type</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ config('constant.job_type')[$job->job_type_id] }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Count</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_count }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Working Time</label>
                            </div>
                            <div class="col-lg-4">
                                <p class="font-weight-bold">{{ $job->job_start_time ." ". $job->job_end_time }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Posted On</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ date('m/d/Y',strtotime($job->created_at)) }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Location</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->location }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Description</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{!! $job->description !!}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Salary Range</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->currency->code ." ". $job->min_salary." - ".$job->currency->code ." ". $job->max_salary }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Required Experience (Year)</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->required_experience }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Key Skills</label>
                            </div>
                            <div class="col-lg-4">
                                @foreach($job->key_skills as $skill)
                                    <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($skill) ? $skill : "" }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_content')

@endsection
