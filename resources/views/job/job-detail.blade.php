@extends('layouts.app')
@section('content')
@php
$statuss = config('constant.job_status');
$share_url = Request::url();
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
                                            <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt=""
                                    class="w-100">
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="job-details-inner">
                                    <h2 class="job-title">
                                        {{ $job->job_title_id > 0 ? $job->jobTitle->title : $job->other_job_title }}
                                    </h2>
                                    <div class="job-company-location">
                                        <p class="company">
                                            {{ $job->job_type == 1 ? $job->category->title. " | " : ""  }}
                                            {{ Helper::timeAgo($job->created_at) }}</p>
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
                        <span
                            class="job-status status-{{ strtolower($statuss[$job->job_status]) }} mr-2">{{ $statuss[$job->job_status] }}</span>
                        @if(Auth::check())
                        <a href="javascript:;" class="apply-btn" data-id="{{ $job->id }}">Apply</a>
                        @else
                        <a href="{{ route('login') }}" class="apply-btn">Apply</a>
                        @endif
                    </div>
                    <div class="col-lg-auto col-md-4">
                        <input type="hidden" id="link_share" value="{{ $share_url }}" />
                        <h5 class="font-black d-flex align-items-center">Share</h5>
                        <ul class="list social-share">
                            <li class="share-link"><a href="javascript:void(0);" onclick="myFunction()"
                                    title="Copy Link"><i class="icon-share3"></i></a></li>
                            <li class="share-facebook"><a
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ $share_url }}"
                                    target="_blank"><i class="icon-facebook"></i></a></li>
                            <li class="share-twitter"><a
                                    href="https://twitter.com/intent/tweet?text=Mea&amp;url={{ $share_url }}"
                                    target="_blank"><i class="icon-twitter"></i></a></li>
                            <li class="share-linkedin"><a
                                    href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ $share_url }}&amp;title=Mea&amp;summary="
                                    target="_blank"><i class="icon-linkedin2"></i></a></li>
                        </ul>
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
                <p>{{ $job->is_paid ? ($job->currency->code ." ". $job->min_salary." - ".$job->currency->code ." ". $job->max_salary) : "-" }}
                </p>
            </div>
            <div class="job-detail-list">
                <label class="lable">Working Time:</label>
                <p>{{ $job->job_start_time ." to ". $job->job_end_time }}</p>
            </div>
            <div class="job-detail-list">
                <label class="lable">Timezone:</label>
                <p>{{ $job->time_zone ?? "-" }}</p>
            </div>
            <div class="job-detail-list">
                <label class="lable">Skills:</label>
                <ul class="skills">
                    @foreach($job->key_skills as $skill)
                    <li>{{ isset($skill) ? $skill : "" }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="job-detail-list">
                <label class="lable">What shifts will they work?:</label>
                <div class="job_shift_wrap">
                    <div class="radios">
                        @foreach (config('constant.SHIFT') as $day => $shift)
                        <div class="form-group">
                            <h6>{{ array_keys($shift)[0] }}</h6>
                            <div class="form-radio-group">
                                @foreach ($shift[array_keys($shift)[0]] as $key => $value)
                                @php
                                $shifts = $job->jobShift->toArray()
                                @endphp
                                {{-- <label class="radio-inline {{ $class }}">
                                <i class="{{ $value }}"></i>
                                <input type="radio" class="job_type shift-radio" name="shift[{{ $day }}]"
                                    value="{{ $key }}" {{ $checked }}>
                                </label> --}}
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


<!-- apply job modal -->
<div id="apply-job-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <p class="already_applied_text text-success" style="display: none"></p>
            <form class="apply_job_form" action="{{ route('job.apply-job') }}" class="form-horizontal" data-fouc
                method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mt-md-2 ckeditor">
                        <label class="col-form-label">Additional Information:<span
                                class="required-star-color">*</span></label>
                        <div class="input-group custom-start">
                            <textarea name="description" id="description" rows="5" placeholder="Message"
                                class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                </div>
                <input type="hidden" name="job_id" class="job_id">
                <input type="hidden" name="job_applied_id" class="job_applied_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('footer_script')
<script>
    var check_already_applied_link = "{{ route('job.check-apply-job') }}";
    function myFunction() {
        var dummy = document.createElement('input'),
            text = window.location.href;
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        setTooltip('Copied');
        hideTooltip();
    }
    $('.share-link a').tooltip({
        trigger: 'click',
        placement: 'bottom'
    });

    function setTooltip(message) {
        $('.share-link a').tooltip('hide')
        .attr('data-original-title', message)
        .tooltip('show');
    }

    function hideTooltip() {
        setTimeout(function() {
            $('.share-link a').tooltip('hide');
        }, 1000);
    }
</script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/apply.js') }}"></script>
@endsection