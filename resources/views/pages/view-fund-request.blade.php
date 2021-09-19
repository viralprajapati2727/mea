@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="page-wraper fund-inner-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="fund-left-content">
                        <div class="header">
                            <h2 class="title">{{ $fund->title }}</h2>
                            <p class="user">
                                @php
                                    $ProfileUrl = Helper::images(config('constant.profile_url'));
                                    $img_url = (isset($fund->user->logo) && $fund->user->logo != '') ? $ProfileUrl . $fund->user->logo : $ProfileUrl.'default.png';
                                @endphp
                                <img src="{{ $img_url }}">
                                <span>{{ $fund->user->name }}</span>
                            </p>
                        </div>
                        <div class="fund-content">
                            {!! $fund->description !!}               
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fund-sidebar">
                        <div class="sidebar-inner">
                            <div class="siderbar-header">
                                <div class="fund-amount">
                                    <h3>{{ $fund->currency }} {{ $fund->received_amount ? $fund->received_amount : 0 }}</h3><span>raised of {{ $fund->currency }} {{ $fund->amount }} goal</span>
                                </div>
                                <progress id="file" value="{{  round((($fund->received_amount ?? 0) / $fund->amount * 100), 2) }}" title="{{  round((($fund->received_amount ?? 0) / $fund->amount * 100), 2) }} %" max="100"> {{ round((($fund->received_amount ?? 0) / $fund->amount * 100), 2) }}% </progress>
                            </div>
                            <div class="donate-info">
                                <ul>
                                    <li>
                                        <p>{{ $donors ?? 0 }}</p>
                                        <span>Donors</span>
                                    </li>
                                    {{-- <li> 
                                        <p>1.5K</p>
                                        <span>Shares</span>
                                    </li> --}}
                                    {{-- <li>
                                        <p>2.8K</p>
                                        <span>Followers</span>
                                    </li> --}}
                                </ul>
                            </div>
                            <div class="fund-actions">
                                <button class="btn share-btn">Share</button>
                                <button class="btn donate-btn" data-toggle="modal" data-target="#donate" >Donate now</button>
                            </div>
                            <div class="sidebar-footer">
                                <ul>
                                    <li>
                                        <div class="icon"><img src="{{  Helper::images('images/fund/').'graph_icon.png' }}"></div>
                                        <span>{{ $justDonors ?? 0 }} people just donated</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="donate" class="modal fade" tabindex="-1" style="z-index: 99999">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Donate</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form class="appointment_form " action="{{ route('payment') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
                @csrf
                {!! Form::hidden('user_id', $fund->user_id) !!}
                {!! Form::hidden('raise_fund_id', $fund->id) !!}
                {!! Form::hidden('title', $fund->title) !!}
                <div class="modal-body">
                    <div class="row mt-md-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Please enter amount <span class="required-star-color">*</span></label>
                                <input type="number" class="form-control" name="amount" id="Amount" placeholder="0" required min="1" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Donate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 