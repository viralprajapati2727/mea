@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.job_status');
@endphp
<div class="my-jobs">
    <div class="container">
        <div class="page-header page-header-light">
            <div class="header-elements-md-inline job-list-header my-job-list-header">
                <div class="page-title d-flex p-0">
                    <h2 class="font-color page-title">Fund Requests</h2>
                </div>
            </div>
        </div>
        <!-- Content area -->
        <div class="">
            @if(!$funds->isEmpty())
                <div class="col jb_border_bottm_gray d-none d-lg-block job-item jobs-header">
                    <div class="row">
                        <div class="col-3">
                            <h5 class="font-black text-left">Title</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Amount</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Currency</h5>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="font-black">Received Amount</h5>
                        </div>
                        <div class="col-1 text-center">
                            <h5 class="font-black">Donors</h5>
                        </div>
                        <div class="col-1 text-center">
                            <h5 class="font-black">Status</h5>
                        </div>
                    </div>
                </div>
                @forelse ($funds as $fund)
                    <div class="col jb_border_bottm_gray job-item">
                        <div class="row">
                            <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                <div class="jb_company_myjob_title">
                                    <h4 class="font-weight-semibold">
                                        <a href="{{ route('page.fund-requests.view',['id' => $fund->id]) }}" class="">
                                            {{ $fund->title != null ? $fund->title : "" }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class="">{{ $fund->amount }}</div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class="">{{ $fund->currency }}</div>
                            </div>
                            <div class="col-lg-2 col-12 main-duration">
                                <div class="">{{ $fund->received_amount ? $fund->received_amount : 0 }}</div>
                            </div>
                            <div class="col-lg-1 col-12 main-duration">
                                <div class="">{{ $fund->donors }}</div>
                            </div>
                            <div class="col-lg-1 col-12 d-none d-lg-block main-status">
                                <div class=""><span class="status-{{ strtolower($statuss[$fund->status]) }}">{{ config('constant.job_status')[$fund->status] }}</span></div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
                <div class="pagination my-5">
                    {{ $funds->onEachSide(1)->links() }}
                </div>
            @else
            <div class="pagination my-5">No Fund Request Found!!</div>
            @endif
        </div>
    </div>
</div>
@endsection