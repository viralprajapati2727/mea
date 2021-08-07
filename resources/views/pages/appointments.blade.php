@extends('layouts.app')
@section('content')
@php $statuss = config('constant.appointment_status'); @endphp
<div class="my-jobs">
    <div class="page-main">
        <div class="container">
            <div class="appoitnments-btns">
                <div class="job-header-elements d-sm-flex justify-content-center">
                    <a href="{{ route('appointment.index') }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn mr-3">Received Appointments</a>
                    <a href="{{ route('appointment.sent') }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn">Sent Appointments</a>
                </div>
            </div>
            <div class="page-header page-header-light">
                <div class="header-elements-md-inline job-list-header my-job-list-header">
                    <div class="page-title d-flex p-0">
                        <h2 class="font-color page-title">Appointments</h2>
                    </div>
                </div>
            </div>
            <!-- Content area -->
            <div class="">
                @if(!$appointments->isEmpty())
                    <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
                        <div class="row">
                            <div class="col-2">
                                <h5 class="font-black text-left">Name</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Appointment Date</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Appointment Time</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Time</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black">Status</h5>
                            </div>
                            <div class="col-2 text-center">
                                <h5 class="font-black text-center">Action</h5>
                            </div>
                        </div>
                    </div>
                    @forelse ($appointments as $appointment)
                        <div class="col jb_border_bottm_gray job-item">
                            <div class="row">
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                    <div class="jb_company_myjob_title">
                                        <div class="text-muted jb_my_job_company_bottom_location">
                                            <div class="d-block job-address">
                                                <a href="javascript:;" class="appointment-detail" data-id="{{ $appointment->id }}">{{ $appointment->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Appointment Date : </b>&nbsp;</span>{{ $appointment->appointment_date }}</div>
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Appointment Time : </b>&nbsp;</span>{{ $appointment->appointment_time }}</div>
                                </div>
                                <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                    <div class=""><span class="d-inline-block d-lg-none"><b>Time : </b>&nbsp;</span>{{ $appointment->time }}</div>
                                </div>
                                <div class="col-lg-2 col-12 d-none d-lg-block main-status">
                                    <div class=""><span class="status-{{ strtolower($statuss[$appointment->status]) }}">{{ $statuss[$appointment->status] }}</span></div>
                                </div>
                                <!-- desktop only -->
                                <div class="col-lg-2 col-12 d-none d-lg-block main-dropdown">
                                    <div class="text-center">
                                        <div class="list-icons">
                                            <div class="list-icons-item dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="flaticon-menu"></i></a>
                                                <span class="tooltip-arrow"></span>
                                                <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(30px, 30px, 0px);">
                                                    @if(Auth::id() == $appointment->user_id)
                                                        <a href="javascript:;" class="dropdown-item delete-appointment" data-id="{{ $appointment->id }}" data-status="delete"><span class="main-icon-span"><i class="flaticon-trash"></i></span> Delete Appointment</a>
                                                    @endif
                                                    @if($appointment->status == 0 && Auth::id() == $appointment->receiver_id)
                                                        <a href="javascript:;" class="dropdown-item approve-reject" data-id="{{ $appointment->id }}" data-active='1'><span class="main-icon-span"><i class="flaticon-trash"></i></span> Approve Appointment</a>
                                                        <a href="javascript:;" class="dropdown-item approve-reject" data-id="{{ $appointment->id }}" data-active='2'><span class="main-icon-span"><i class="flaticon-trash"></i></span> Reject Appointment</a>
                                                    @endif
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
                        {{ $appointments->onEachSide(1)->links() }}
                    </div>
                @else
                <div class="pagination my-5">No Appointments Found!!</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- appoinment detail modal -->
<div id="appointment-detail" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Detail</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Name:</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_name"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Email:</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_email"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Appointment Date:</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_date"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Appointment Time:</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_appointment_time"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Time Intervel :</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_time"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h2 class="title">Description:</h2>
                    </div>
                    <div class="col-8">
                        <p class="app_description"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript">
    var appointment_detail_link = "{{ route('appointment.detail') }}";
    var appointment_delete_link = "{{ route('appointment.delete') }}";
    var approve_reject_link = "{{ route('appointment.approve-reject') }}";
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/appointment.js') }}"></script>
@endsection