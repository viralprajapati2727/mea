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
                                <progress id="file" value="32" max="100"> 32% </progress>
                            </div>
                            <div class="donate-info">
                                <ul>
                                    <li>
                                        <p>{{ $fund->donors }}</p>
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
                                <button class="btn donate-btn">Donate now</button>
                            </div>
                            <div class="sidebar-footer">
                                <ul>
                                    <li>
                                        <div class="icon"><img src="{{  Helper::images('images/fund/').'graph_icon.png' }}"></div>
                                        <span>{{ $fund->donors }} people just donated</span>
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
@endsection 