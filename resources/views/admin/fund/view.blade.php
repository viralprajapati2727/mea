@extends('admin.app-admin')
@section('title') Fund Request Details @endsection
@section('content')
@php
    $statuss = config('constant.appointment_status');
    $share_url = Request::url();
@endphp
<h6 class="card-title text-center">Fund Request Details</h6>
<div class="card">
    <div class="card-body custom-tabs">
        <div class="row">
            <div class="col-md-10">
                <div class="detail-section">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Title</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->title }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal"><span class='badge badge-success'><a href='javascript:;'>
                            @php
                                if($fund->status == '0'){
                                    echo "PENDING";
                                } else if($fund->status == '1'){
                                    echo "APPROVED";
                                } else {
                                    echo "REJECTED";
                                }
                            @endphp    
                            </a></span></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Currency</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->currency }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Amount</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->amount }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Received Amount</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->received_amount ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Donors</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->donors }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Description</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal"></p>{!! $fund->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
</script>
@endsection
