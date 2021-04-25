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
                <div class="col-md-10">
                    <div class="detail-section">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Type Of Job</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_type == 1? "Post Job" : "Post Request" }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job ID</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_unique_id." | ". Helper::timeAgo($job->created_at)  }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Title</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_title_id > 0 ? $job->jobTitle->title : $job->other_job_title }}</p>
                            </div>
                        </div>
                        @if($job->job_type == 1)
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Business Category</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->category->title  }}</p>
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
                        @endif
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Job Count</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->job_count }}</p>
                            </div>
                        </div>
                        @if($job->job_type == 1)
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
                                <label class="font-weight-bold label-before">TimeZone</label>
                            </div>
                            <div class="col-lg-4">
                                <p class="font-weight-bold">{{ $job->time_zone }}</p>
                            </div>
                        </div>
                        @endif
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
                        @if($job->job_type == 1)
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Salary Range</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->is_paid ? ($job->currency->code ." ". $job->min_salary." - ".$job->currency->code ." ". $job->max_salary) : "" }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Key Skills</label>
                            </div>
                            <div class="col-lg-8">
                                @foreach($job->key_skills as $skill)
                                    <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($skill) ? $skill : "" }}</p>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">What shifts will they work?</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="row mt-md-2 job_shift_wrap w-100">
                                    <div class="col-12">
                                        <div class="radios">
                                            @foreach (config('constant.SHIFT') as $day => $shift)
                                                <div class="form-group">
                                                    <h6>{{ array_keys($shift)[0] }}</h6>
                                                    <div class="form-radio-group">
                                                        @foreach ($shift[array_keys($shift)[0]] as $key => $value)
                                                        @php
                                                            $shifts = $job->jobShift->toArray()
                                                        @endphp
                                                           @if($key == 1) {{-- day shift --}}
                                                           <label
                                                               class="radio-inline {{ $shifts[$day-1]['day_shift_val'] == 1 ? "active" : "" }} ">
                                                               <i class="{{ $value }}"></i>
                                                               <input type="checkbox" class="job_type shift-radio" name="day_shift[{{ $day }}]"
                                                                   value="{{ 1 }}" {{ $shifts[$day-1]['day_shift_val'] == 1 ? "checked" : "" }}>
                                                           </label>
                                                           @else {{-- night shift --}}
                                                           <label
                                                               class="radio-inline {{ $shifts[$day-1]['night_shift_val'] == 1 ? "active" : "" }} ">
                                                               <i class="{{ $value }}"></i>
                                                               <input type="checkbox" class="job_type shift-radio" name="night_shift[{{ $day }}]"
                                                                   value="{{ 1 }}" {{ $shifts[$day-1]['night_shift_val'] == 1 ? "checked" : "" }}>
                                                           </label>
                                                           @endif                                            
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Find Team Member?</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->is_find_team_member == 1 ? "Yes" : "No" }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Find Team Content</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $job->find_team_member_text}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_content')

@endsection
