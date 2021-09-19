@extends('layouts.app')
@section('content')
@php
$statuss = config('constant.job_status');
@endphp
<div class="my-jobs">
  <div class="container">
    <div class="page-header page-header-light">
      <div class="page-title d-flex p-0 d-flex justify-content-between">
        <h2 class="font-color page-title">Donors List</h2>
        <div class="p-2">
          <a href="{{ route('startup.raise-fund.create', ["id" => $id, 'action' => "view"]) }}" class="btn btn-primary">
            Back
          </a>
        </div>
      </div>
    </div>
    <!-- Content area -->
    <div class="">
      @if(!$payments->isEmpty())
      <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
        <div class="row">
          <div class="col-6">
            <h5 class="font-black text-left">Payment Id</h5>
          </div>
          <div class="col-2 text-center">
            <h5 class="font-black">Email</h5>
          </div>
          <div class="col-2 text-center">
            <h5 class="font-black">Amount</h5>
          </div>
          <div class="col-2 text-center">
            <h5 class="font-black">Payment Status</h5>
          </div>
        </div>
      </div>
      @forelse ($payments as $payment)
      @php
      $paymentObject = json_decode($payment->payment_object);
      @endphp
      <div class="col jb_border_bottm_gray job-item">
        <div class="row">
          <div class="col-lg-6 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
            <div class="jb_company_myjob_title">
              <h6 class="font-weight-semibold">
                {{ $payment->payment_object != null ? $paymentObject->id : "-" }}
              </h6>
            </div>
          </div>
          <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
            <div class="">{{ $paymentObject->customer_details->email ?? '-' }}</div>
          </div>
          <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
            <div class="">{{ $payment->amount }}</div>
          </div>
          <div class="col-lg-2 col-12 main-duration">
            <div class="">{{ ucfirst($payment->payment_status) }}</div>
          </div>
        </div>
      </div>
      @empty
      @endforelse
      <div class="pagination my-5">
        {{ $payments->onEachSide(1)->links() }}
      </div>
      @else
      <div class="pagination my-5">No Donors Found!!</div>
      @endif
    </div>
  </div>
</div>
@endsection