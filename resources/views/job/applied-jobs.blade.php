@extends('layouts.app')
@section('content')
@php
$statuss = config('constant.job_status');
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
                                {{-- <span class="breadcrumb-item text-secondary">{{ $job_id }}</span> --}}
                                <span class="breadcrumb-item active ">Applied Jobs</span>
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
                                <h2 class="font-color page-title">Applied Jobs</h2>
                            </div>
                            <div class="job-apply-elements">
                                <div class="header-elements-md-inline job-apply-div my-2">
                                    <div class="job-apply-text-div">
                                        <div class="input-group jb_mr-1">
                                            <div class="input-group-prepend">
                                                <i class="flaticon-search"></i>
                                            </div>
                                            {{-- <input type="text" name="keyword" class="form-control home-banner-last-input" placeholder="Search Applications" value="{{ array_key_exists('keyword',$params) ? $params['keyword'] : old('keyword') }}">
                                            --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Content area -->
                <div class="">
                @if(!$jobs->isEmpty())
                    <div class="col pt-4 jb_border_bottm_gray px-0 d-none d-md-block">
                        <div class="row">
                            <div class="col-6  text-center">
                                <h5 class="font-black">Job Title</h5>
                            </div>
                            <div class="col-3 text-center">
                                <h5 class="font-black">Status</h5>
                            </div>
                            <div class="col-3 col-lg-2 text-center">
                                <h5 class="font-black text-center">Action</h5>
                            </div>
                        </div>
                    </div>
                    @forelse ($jobs as $job)
                    <div class="col jb_border_bottm_gray job-item">
                        <div class="row">
                            <div class="col-lg-6 col-12 d-lg-block header-elements-inline align-items-baseline ">
                                <div class="jb_company_myjob_title">
                                    <h4 class="font-weight-semibold">
                                        <a href="{{ route('job.job-detail',['id' => $job->job->job_unique_id]) }}"
                                            class="font-black">{{ $job->job->job_title_id > 0 ? $job->job->jobTitle->title : $job->job->other_job_title }}</a>
                                    </h4>
                                    <div class="text-muted jb_my_job_company_bottom_location">
                                        <div class="d-block job-address">
                                            <i class="flaticon-pin mr-1"></i>
                                            {{ $job->job->location }}
                                        </div>
                                        @if($job->job->job_type == 1)
                                        <div class="d-block">
                                            <i
                                                class="flaticon-wall-clock mr-1"></i>{{ config('constant.job_type')[$job->job->job_type_id] }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- mobile only -->
                                <div class="d-block d-lg-none main-dropdown text-center">
                                    <div class="d-inline-block mr-1 mr-sm-2 main-status">
                                        <span class="status-{{ strtolower(config('constant.job_status')[$job->job->job_status]) }}">{{ config('constant.job_status')[$job->job->job_status] }}</span>
                                    </div>
                                    <div class="d-block">
                                        <h4 class="font-weight-semibold text-center">
                                            <a href="{{ route('job.cancel-job',['id' => $job->id]) }}" class="text-danger">
                                                Cancel
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                <!--end mobile only -->
                            </div>
                            <div class="col-lg-3 col-12 d-none d-lg-block main-status">
                                <div class="text-center">
                                    <span class="text-center status-{{ strtolower($statuss[$job->job->job_status]) }}">{{ config('constant.job_status')[$job->job->job_status] }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 d-none d-lg-block main-status">
                                <h4 class="font-weight-semibold text-center">
                                    <a href="{{ route('job.cancel-job',['id' => $job->id]) }}" class="text-danger">
                                        Cancel
                                    </a>
                                </h4>
                            </div>
                            <!-- end desktop only -->
                        </div>
                    </div>
                    @empty
                    @endforelse
                    <div class="pagination my-5">
                        {!! $jobs->links() !!}
                    </div>
                    @else
                    <div class="my-5">No Applied Jobs Found!!</div>
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

        // $("#search-form").submit(function(){
        //     if($("input.job-status:checkbox:checked").length > 0){
        //         return true;
        //     }else{
        //         swal({
        //             title: PLEASE_SELECT_ATLEAST_ONE_STATUS_FOR_FILTER,
        //             type: 'warning',
        //             confirmButtonText: Btn_Ok,
        //             confirmButtonClass: 'btn btn-primary',
        //             cancelButtonClass: 'btn btn-danger',
        //             // confirmButtonColor: "#ff7043",
        //         });
        //         return false;
        //     }
        // });

        // $(document).on('change','.apply-status',function(){
        //     _this = $(this);
        //     if(_this.val() != "" && _this.data('id') != "" && _this.val() != _this.data('current')){
        //         swal({
        //             title: "Are you sure you want to change status ?",
        //             type: 'warning',
        //             showCancelButton: true,
        //             confirmButtonText: "Yes",
        //             cancelButtonText: "No",
        //             confirmButtonClass: 'btn btn-primary',
        //             cancelButtonClass: 'btn btn-grey',
        //             buttonsStyling: false
        //         }).then(function (confirm) {
        //             if(confirm.value !== "undefined" && confirm.value){
        //                 $.ajax({
        //                     url: status_url,
        //                     type: 'POST',
        //                     data: {
        //                         id : _this.data('id'),
        //                         status : _this.val(),
        //                     },
        //                     beforeSend: function(){
        //                         $('body').block({
        //                             message: '<i class="icon-spinner4 spinner"></i><br>'+ "Please Wait..",
        //                             overlayCSS: {
        //                                 backgroundColor: '#000',
        //                                 opacity: 0.15,
        //                                 cursor: 'wait'
        //                             },
        //                             css: {
        //                                 border: 0,
        //                                 padding: 0,
        //                                 backgroundColor: 'transparent'
        //                             }
        //                         });
        //                     },
        //                     success: function(response) {
        //                         if(response.status == 200){
        //                             swal({
        //                                 title: response.msg_success,
        //                                 type: "success",
        //                                 confirmButtonText: "OK",
        //                                 confirmButtonClass: 'btn btn-primary',
        //                             }).then(function (){
        //                                 window.location.reload(true);
        //                             });
        //                         }else{
        //                             swal({
        //                                 title: response.msg_fail,
        //                                 confirmButtonClass: 'btn btn-primary',
        //                                 type: "error",
        //                                 confirmButtonText: "OK",
        //                             });
        //                         }
        //                     },
        //                     complete: function(){
        //                         $('body').unblock();
        //                     }
        //                 });
        //             }else{
        //                 _this.val(_this.data('current')).change();
        //                 return;
        //             }
        //         }, function (dismiss) {
        //         });
            // }
        // });
    });
</script>
@endsection