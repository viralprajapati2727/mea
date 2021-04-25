@extends('admin.app-admin')
@section('title') Appointment Detail @endsection
@section('page-header')
    @php
        $statuss = config('constant.appointment_status');
    @endphp
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <span class="breadcrumb-item active">Appointment Detail</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <h6 class="card-title text-center">Appointment Details</h6>
    <div class="card">
        <div class="card-body custom-tabs">
            <div class="row">
                <div class="col-md-8">
                    <div class="detail-section">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Name</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $appointment->name }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Email</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $appointment->user->email  }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Appointment Date & Time</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $appointment->appointment_date }} {{ $appointment->appointment_time }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Time</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $appointment->time }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Description</label>
                            </div>
                            <div class="col-lg-8">
                                <p>{!! $appointment->description !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12 py-0">
                        <div class="row">
                            <div class="col-lg-3 pr-0">
                                <label class="font-weight-bold">Status : </label>
                            </div>
                            <div class="col-lg-8 pl-0">
                                <span class="custom-badge badge badge-success">{{ $statuss[$appointment->status] }}</span>
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
