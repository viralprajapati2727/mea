@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.appointment_status');
    $share_url = Request::url();
    
@endphp
<div class="page-main">
    <div class="page-wraper">
        <div class="job-top-details">
            <div class="container">
                <div class="job-inner-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="job-details-left">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="job-details-inner">
                                            <h2 class="job-title">{{ $fund->title ?? ""}}</h2>
                                            <div class="mr-3 mt-2">
                                                <span class="job-status status-{{ strtolower($statuss[$fund->status]) }} mr-2">{{ $statuss[$fund->status] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('startup.donor-list', ["id" => $fund->id]) }}" class="btn btn-primary job-details-inner">
                                            View Donor List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="job-wrap job-details">
                    <div class="job-detail-list">
                        <label class="lable">Currency:</label>
                        <p>{{ $fund->currency ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Amount:</label>
                        <p>{{ $fund->amount ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Received Amount:</label>
                        <p>{{ $fund->received_amount ?? 0 }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Views:</label>
                        <p>{{ $fund->views ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Donors:</label>
                        <p>{{ $fund->donors ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Request Time:</label>
                        <p>{{ $fund->created_at ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Startup Description:</label>
                        <p>{!! $fund->description ?? "" !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_script')
    
@endsection