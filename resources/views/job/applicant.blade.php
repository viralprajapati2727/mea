@extends('layouts.app')
@section('content')
@php
$job_apply_status = config('constant.job_apply_status');
@endphp
<div class="page-content pt-0">
    <div class="content-wrapper my-job-company applicants">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrumb-line breadcrumb-line-light pl-0 mt-3">
                            <div class="breadcrumb breadcrumb-caret">
                                <a href="{{ route('job.my-jobs') }}" class="breadcrumb-item text-secondary">Jobs</a>
                                <span class="breadcrumb-item text-secondary">{{ $job_id }}</span>
                                <span class="breadcrumb-item active ">Applicants</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="page-header page-header-light">
                    <form class="form-horizontal" id="search-form" autocomplete="off">
                        <div class="header-elements-md-inline job-apply-header">
                            <div class="page-title p-0">
                                <h2 class="font-color page-title">Applicants</h2>
                            </div>
                            <div class="job-apply-elements">
                                <div class="header-elements-md-inline job-apply-div my-2">
                                    <div class="job-apply-text-div">
                                        <div class="input-group jb_mr-1">
                                            <div class="input-group-prepend">
                                                <i class="flaticon-search"></i>
                                            </div>
                                            <input type="text" name="keyword" class="form-control home-banner-last-input" placeholder="Search Applications" value="{{ array_key_exists('keyword',$params) ? $params['keyword'] : old('keyword') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="job-applicant-status">
                                <select name="status" class="form-control-select2 select2-hidden-accessible">
                                    <option value="">All</option>
                                    @foreach ($job_apply_status as $key => $status)
                                        <option value="{{ $key }}" {{ isset($params['status']) ? ($key === $params['status'] ? 'selected' : '') : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="header-elements-md-inline job-apply-sub-header mb-2">
                            <div class="job-apply-btn-div">
                                <input type="submit" name="apply" value="Apply" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Content area -->
                <div class="">
                    @if(!$applicants->isEmpty())
                    <div class="col jb_border_bottm_gray d-none d-md-block applicants-header">
                        <div class="row">
                            <div class="col-5">
                                <h5 class="font-black">Applicant List</h5>
                            </div>
                            <div class="col-1 d-none d-lg-block  text-center">
                                <h5 class="font-black">Age</h5>
                            </div>
                            <div class="col-md-2 col-lg-2 text-center">
                                <h5 class="font-black">Duration</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Status</h5>
                            </div>
                            <div class="col-3 col-lg-2 text-center">
                                <h5 class="font-black text-center">Action</h5>
                            </div>
                        </div>
                    </div>
                    @forelse ($applicants as $applicant)
                    <div class="col jb_border_bottm_gray applicants-item">
                        <div class="row">
                            <div class="col-md-5 col-12 d-lg-block header-elements-inline align-items-baseline">
                                <div class="jb_company_myjob_title">
                                    @php
                                    $img_url = Helper::images(config('constant.profile_url') . 'default.png');
                                    if(isset($applicant->user->logo) && $applicant->user->logo != ""){
                                        $img_url = Helper::images(config('constant.profile_url') . $applicant->user->logo);
                                    }
                                    @endphp
                                    <div class="media-main">
                                        <div class="ml-3 profile-bg-logo border-rounded profile-width-80" style="background-image:url('{{ $img_url }}')"></div>
                                        <div class="media-body">
                                            <h4 class="m-0"><a href="#" class="font-black">{{ $applicant->user->name }}</a></h4>
                                            <div class="font-gray jb_my_job_company_bottom_location">
                                                {{--  <div>{{ isset($applicant->profile->candidateJobTitleSought[0]) && isset($applicant->profile->candidateJobTitleSought[0]->jobTitleSoughtLang[0]) ? $applicant->profile->candidateJobTitleSought[0]->jobTitleSoughtLang[0]->title : "" }}</div>  --}}
                                                <div class="d-sm-inline d-block mr-3">
                                                    <i class="flaticon-pin mr-1"></i>
                                                    {{ $applicant->user->userProfile->city}}
                                                </div>
                                            </div>
                                            <div class="font-gray d-lg-none">
                                                Age: {{ Helper::ageCalculate($applicant->user->userProfile->dob) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 d-none d-lg-block col-12 main-duration">
                                <div class="">{{ Helper::ageCalculate($applicant->user->userProfile->dob) }}</div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-12 main-duration">
                                <div class="text-center">{{Helper::timeAgo($applicant->created_at)}}</div>
                            </div>
                            <div class="col-md-2 col-12 main-status">
                                <div class="text-center"><span class="status-{{ strtolower($job_apply_status[$applicant->job_applied_status_id]) }}">{{ $job_apply_status[$applicant->job_applied_status_id] }}</span></div>
                            </div>
                            <div class="col-md-3 col-lg-2 col-12 main-status">
                                <select class="form-control-select2 apply-status" name="apply-status" data-id="{{ $applicant->id }}" data-current="{{ $applicant->job_applied_status_id }}">
                                    @foreach ($job_apply_status as $key => $status)
                                        
                                        <option value="{{ $key }}" {{ $key == $applicant->job_applied_status_id ? 'selected' : '' }}>{{ $status }}</option>
                                            {{--  @if($key >= $applicant->job_applied_status_id)
                                                <option value="{{ $key }}" {{ $key == $applicant->job_applied_status_id ? 'selected' : '' }}>{{ trans('page.'.$status) }}</option>
                                            @endif                                                                       --}}
                                    @endforeach
                                    {{-- @else
                                        <option value="{{ $applicant->job_applied_status_id }}" selected>{{ trans('page.'.$job_apply_status[$applicant->job_applied_status_id]) }}</option> --}}
                                </select>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                    <div class="pagination my-5">
                        {!! $applicants->appends($params)->links() !!}
                    </div>
                    @else
                        <div class="my-5">No Applicant Found!!</div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /content area -->
    </div>
</div>
@endsection
@section('footer_script')
<script>
    var status_url = "{{ route('job.change-applicant-status') }}";
    $(document).ready(function(){
        // Initialize
        var $select = $('.form-control-select2').select2({
            // minimumResultsForSearch: Infinity,
            width: '100%',
        
        });

        // Trigger value change when selection is made
        $select.on('change', function() {
            $(this).trigger('blur');
        });

        $("#search-form").submit(function(){
            if($("input.job-status:checkbox:checked").length > 0){
                return true;
            }else{
                swal({
                    title: PLEASE_SELECT_ATLEAST_ONE_STATUS_FOR_FILTER,
                    type: 'warning',
                    confirmButtonText: Btn_Ok,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger',
                    // confirmButtonColor: "#ff7043",
                });
                return false;
            }
        });

        $(document).on('change','.apply-status',function(){
            _this = $(this);
            if(_this.val() != "" && _this.data('id') != "" && _this.val() != _this.data('current')){
                swal({
                    title: "Are you sure you want to change status ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-grey',
                    buttonsStyling: false
                }).then(function (confirm) {
                    if(confirm.value !== "undefined" && confirm.value){
                        $.ajax({
                            url: status_url,
                            type: 'POST',
                            data: {
                                id : _this.data('id'),
                                status : _this.val(),
                            },
                            beforeSend: function(){
                                $('body').block({
                                    message: '<i class="icon-spinner4 spinner"></i><br>'+ "Please Wait..",
                                    overlayCSS: {
                                        backgroundColor: '#000',
                                        opacity: 0.15,
                                        cursor: 'wait'
                                    },
                                    css: {
                                        border: 0,
                                        padding: 0,
                                        backgroundColor: 'transparent'
                                    }
                                });
                            },
                            success: function(response) {
                                if(response.status == 200){
                                    swal({
                                        title: response.msg_success,
                                        type: "success",
                                        confirmButtonText: "OK",
                                        confirmButtonClass: 'btn btn-primary',
                                    }).then(function (){
                                        window.location.reload(true);
                                    });
                                }else{
                                    swal({
                                        title: response.msg_fail,
                                        confirmButtonClass: 'btn btn-primary',
                                        type: "error",
                                        confirmButtonText: "OK",
                                    });
                                }
                            },
                            complete: function(){
                                $('body').unblock();
                            }
                        });
                    }else{
                        _this.val(_this.data('current')).change();
                        return;
                    }
                }, function (dismiss) {
                });
            }
        });
    });
</script>
@endsection
