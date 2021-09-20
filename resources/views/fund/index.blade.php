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
                <div class="job-header-elements d-sm-flex">
                    @if ($stripeAccountExists)
                    <a href="{{ route('startup.raise-fund.create',['action'=>'create']) }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn"><i class="flaticon-business-idea"></i>Raise Fund Request </a>                    
                    @else
                    <a href="{{ route("bank-account") }}" class="btn-primary jb_btn jb_nav_btn_link post-job-btn">
                        Add bank account.
                    </a>
                    @endif
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
                        <div class="col-1 text-center">
                            <h5 class="font-black text-center">Action</h5>
                        </div>
                    </div>
                </div>
                @forelse ($funds as $fund)
                    <div class="col jb_border_bottm_gray job-item">
                        <div class="row">
                            <div class="col-lg-3 col-12 d-lg-block header-elements-inline align-items-baseline text-left">
                                <div class="jb_company_myjob_title">
                                    <h4 class="font-weight-semibold">
                                        {{-- <a href="{{ route('job.job-detail',['id' => $job->job_unique_id]) }}" class="font-black"> --}}
                                            {{ $fund->title != null ? $fund->title : "" }}
                                        {{-- </a> --}}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class=""><span class="d-inline-block d-lg-none table-title">Amount</span>{{ $fund->amount }}</div>
                            </div>
                            <div class="col-lg-2 col-12 d-lg-block header-elements-inline main-job-id">
                                <div class=""><span class="d-inline-block d-lg-none table-title">Currency</span>{{ $fund->currency }}</div>
                            </div>
                            <div class="col-lg-2 col-12 main-duration">
                                <div class=""><span class="d-inline-block d-lg-none table-title">Received Amount</span>{{ $fund->received_amount ? $fund->received_amount : 0 }}</div>
                            </div>
                            <div class="col-lg-1 col-12 main-duration">
                                <div class=""><span class="d-inline-block d-lg-none table-title">Donors</span>{{ $fund->donors }}</div>
                            </div>
                            <div class="col-lg-1 col-12 d-none d-lg-block main-status">
                                <div class=""><span class="d-inline-block d-lg-none table-title">Status</span><span class="status-{{ strtolower($statuss[$fund->status]) }}">{{ config('constant.job_status')[$fund->status] }}</span></div>
                            </div>
                            <!-- desktop only -->
                            <div class="col-lg-1 col-12 d-lg-block main-dropdown">
                                <div class="text-center">
                                    <div class="list-icons">
                                        <div class="list-icons-item dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="flaticon-menu"></i></a>
                                            <span class="tooltip-arrow"></span>
                                            <div class="dropdown-menu dropdown-menu-right jb_company_dropdown_nav" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(30px, 30px, 0px);">
                                                <a href="{{ route('startup.raise-fund.create',['action'=>'view','id' => $fund->id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-user"></i></span>View</a>
                                                @if($fund->status == 0)
                                                    <a href="{{ route('startup.raise-fund.create',['action'=>'create','id' => $fund->id]) }}" class="dropdown-item"><span class="main-icon-span"><i class="flaticon-edit"></i></span> Edit</a>
                                                @endif
                                                <a href="javascript:;" class="dropdown-item call-action" data-id="143" data-status="delete"><span class="main-icon-span"><i class="flaticon-trash"></i></span> Delete</a>
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
                    {{ $funds->onEachSide(1)->links() }}
                </div>
            @else
            <div class="pagination my-5">No Fund Request Found!!</div>
            @endif
        </div>
    </div>
</div>
@endsection