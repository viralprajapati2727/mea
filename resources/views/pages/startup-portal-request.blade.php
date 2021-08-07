@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.job_status');
@endphp
<div class="page-main">
    <div class="my-jobs">
        <div class="container">
            <div class="page-header page-header-light">
                <div class="header-elements-md-inline job-list-header my-job-list-header">
                    <div class="page-title d-flex p-0">
                        <h2 class="font-color page-title">Startup Portal Requests</h2>
                    </div>
                </div>
            </div>
            <!-- Content area -->
            <div class="">
                @if(!$requests->isEmpty())
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
                    @forelse ($requests as $startup)
                        <div class="col jb_border_bottm_gray job-item">
                            <div class="row">
                                <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                    <div class="jb_company_myjob_title">
                                        <h4 class="font-weight-semibold">
                                            {{ $startup->startupDetails->name != null ? $startup->startupDetails->name : "" }}
                                        </h4>
                                    </div>
                                
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Startup industry : </b>&nbsp;</span>{{ $startup->startupDetails->industry ?? "" }}</div>
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b> </b>&nbsp;</span>{{ $startup->startupDetails->location ?? "" }}</div>
                                </div>
                                <div class="col-lg-2 col-12 main-duration">
                                    @isset($startup->startupDetails)
                                    <div class="">{{ config('constant.stage_of_startup')[$startup->startupDetails->stage_of_startup] }}</div>
                                    @else
                                        -
                                    @endisset
                                </div>
                                <div class="col-lg-1 col-12 d-none d-lg-block main-status">
                                    <div class="">
                                    @isset($startup->startupDetails)
                                        <span class="status-{{ strtolower($statuss[$startup->startupDetails->status]) }}">{{ config('constant.job_status')[$startup->startupDetails->status] }}</span>
                                    @else
                                        -
                                    @endisset
                                </div>
                                </div>
                                <div class="col-lg-2 col-12 main-dropdown">
                                    <div class="text-center">
                                        @if ($startup->id != 0)
                                            -
                                        @else
                                            <a href='javascript:;' class='approve-reject text-success' data-id='{{ $startup->id }}' data-active='1'>Approve</a>&nbsp;/&nbsp;
                                            <a href='javascript:;' class='approve-reject text-danger' data-id='{{ $startup->id }}' data-active='2'>Reject</a>&nbsp;&nbsp;
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    <div class="pagination my-5">
                        {{ $requests->onEachSide(1)->links() }}
                    </div>
                @else
                <div class="pagination my-5">No Startups Found!!</div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
        $(document).on('click','.approve-reject', function() {
        var $this = $(this);
        var id = $this.attr('data-id');
        var status = $this.attr('data-active');
        
        var dialog_title = (status == 1 ? "Are you sure you want to approve this request?" : "Are you sure you want to reject this request?");
        const approve_reject_link = "{{ route('startup-portal-request-action') }}";
      
        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: approve_reject_link,
                    type: 'POST',
                    data: { id : id, status : status},
                    beforeSend: function(){
                        $('body').block({
                            message: '<i class="icon-spinner4 spinner"></i><br>Please Wait...',
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
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                             
                                $this.parent('span').hide();
                                
                                if(status == 1){
                                    var action_link = "<span class='badge badge-success'><a href='javascript:;'>APPROVED</a></span>";
                             
                                    $this.parents('td').find('.after_approve_reject').html(action_link);
                                }

                                if(status == 2){
                                    var action_link = "<span class='badge badge-danger'><a href='javascript:;'>REJECTED</a></span>";
                                    $this.parents('td').find('.after_approve_reject').html(action_link);
                                }

                                window.location.reload();
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    complete: function(){
                        $('body').unblock();
                    }
                });
            }
        });
    });

</script>
@endsection
