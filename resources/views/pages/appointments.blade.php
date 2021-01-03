@extends('layouts.app')
@section('content')
@php $statuss = config('constant.appointment_status'); @endphp
<div class="my-jobs">
    <div class="container">
        <div class="page-header page-header-light">
            <div class="header-elements-md-inline job-list-header my-job-list-header">
                <div class="page-title d-flex p-0">
                    <h2 class="font-color page-title">Appointments</h2>
                </div>
                <div class="job-header-elements d-sm-flex">
                    <a href="javascript:;" class="btn-primary jb_btn jb_nav_btn_link post-job-btn" data-toggle="modal" data-target="#appointment">Request Appointment</a>
                </div>
            </div>
        </div>
        <!-- Content area -->
        <div class="">
            @if(!$appointments->isEmpty())
                <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
                    <div class="row">
                        <div class="col-3">
                            <h5 class="font-black text-left">Name</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Date</h5>
                        </div>
                        <div class="col-3 text-center">
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
                            <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                <div class="jb_company_myjob_title">
                                    <div class="text-muted jb_my_job_company_bottom_location">
                                        <div class="d-block job-address">
                                            {{ $appointment->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class=""><span class="d-inline-block d-lg-none"><b>Date : </b>&nbsp;</span>{{ $appointment->appointment_date }}</div>
                            </div>
                            <div class="col-lg-3 col-12 d-lg-block header-elements-inline main-job-id">
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
                                                {{-- <a href="https://staging.jobaroot.com/view-applicant/J000143" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-user"></i></span>View Applicants</a> --}}
                                                {{--  <a href="{{ route('job.fill-job',['job_unique_id' => $job->job_unique_id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit Job</a>  --}}
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
                    {{ $appointments->onEachSide(1)->links() }}
                </div>
            @else
            <div class="pagination my-5">No Appointments Found!!</div>
            @endif
        </div>
    </div>
</div>


<!-- appoinment modal -->
<div id="appointment" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book Appointment</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            
            <form class="appointment_form " action="{{ route('appointment.update-appointment') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row mt-md-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Name <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ old('name', isset($profile->name) ? $profile->name:'' ) }}" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your Email" value="{{ old('email', isset($profile->email) ? $profile->email : '' ) }}"  disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-md-2">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label class="form-control-label">Date <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control birthdate" name="date" id="date" placeholder="Select Date of Appintment" value="" >
                                <div class="date-of-birth-icon">
                                    <i class="flaticon-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Time Intervel <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="time" id="time" placeholder="Enter Time Intervel" value="" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-md-2">
                        <label class="col-form-label">Appointment Details:<span class="required-star-color">*</span></label>
                        <div class="input-group custom-start">
                            <textarea name="description" id="description" rows="5" placeholder="Enter Appointment Details" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Submit form</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/pages/appointment.js') }}"></script>
@endsection