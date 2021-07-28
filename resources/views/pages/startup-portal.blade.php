@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="business-ideas-wraper">
        <div class="top-title-wrap">
            <div class="container">
                <h1>Startup Portal</h1>
            </div>
        </div>
        <div class="our-process">
            <div class="container">
                <div class="title">
                    <h2>Our Process</h2>
                </div>
                <div class="proceess-step">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-deal"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Introduce your company & tell us your story</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-question"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Answer a few questions</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-accounting"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We work on a financial projection model</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-cloud"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We set up a cloud based syatem (if needed)</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-deal"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Receive financial statements</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    {{-- <i class="flaticon-checklist"></i> --}}
                                    <img src="{{ Helper::assets('images/white-checklist.png') }}">
                                    <img src="{{ Helper::assets('images/hover-checklist.png') }}">
                                </div>
                                <div class="process-title">
                                    <h3>We review your Business Plan</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="process-bottom">
                    <p>A business begins with the plan and financial tools for success we help you shapw and define
                        both.
                        Our process appropriately situates an entrepreneur to <strong>define and namage the business
                            pan</strong> and <strong>avoid the pitfalls</strong> of getting buried in the details.</p>
                </div>
            </div>
        </div>
        <div class="building-plan">
            <div class="container">
                <div class="title">
                    <h2>Building Your Plan</h2>
                </div>
                <p>The fear concern, time and resources needed for setting up astartup seems daunting at first glance -
                    but it doesn't have to be!
                    Startup Portal evolved from years of experience in capital raising cash management, financial
                    reporting and managing the day to day.</p>
            </div>
        </div>
        @auth
        @if (Auth::user()->type == config('constant.USER.TYPE.ENTREPRENEUR'))
        <div class="get-started-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <div class="left-content">
                            <h2>Ready to get started? Click here to schedule a free consultation.</h2>
                        </div>
                    </div>
                    <div class="col-md-3 justify-content-end">
                        <div class="right-button">
                            <a href="{{ route('startup-portal') }}">Click Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endauth
        @if(!$startups->isEmpty() && $startups->count())
        <div class="browse-plans">
            <div class="container">
                <div class="title">
                    <h2>Browse Business Plan/Idea
                </div>
                <div class="card">
                    <div class="card-body">
                        @forelse ($startups as $key => $startup)
                        @php
                        $businessUrl = Helper::images(config('constant.business_plan'));
                        $financialUrl = Helper::images(config('constant.financial'));
                        $pitchdeckUrl = Helper::images(config('constant.pitch_deck'));

                        $exists_businessplan = "";
                        $exists_financial = "";
                        $exists_pitch_deck = "";

                        if(isset($startup)){
                        if($startup->business_plan != ""){
                        $is_same_business_plan = true;
                        $exists_businessplan = $businessUrl.$startup->business_plan;
                        }
                        if($startup->financial != ""){
                        $is_same_financial = true;
                        $exists_financial = $financialUrl.$startup->financial;
                        }
                        if($startup->pitch_deck != ""){
                        $is_same_pitch_deck = true;
                        $exists_pitch_deck = $pitchdeckUrl.$startup->pitch_deck;
                        }
                        }

                        @endphp
                        <div class="mt-2">
                            <div class="p-2 my-2 border rounded">
                                <div class="member-detail">
                                    <h2 class="name">{{ $startup->name }}</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Industry :</strong>
                                            <div class="skills">
                                                <p>{{ $startup->industry ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Stage of Startup :</strong>
                                            <div class="font-normal">
                                                <p>
                                                    {{ config("constant.stage_of_startup")[$startup->stage_of_startup] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>What’s the most important next step for your startup? :</strong>
                                            <div class="font-normal">
                                                <p>
                                                    {{ $startup->important_next_step > 0 ? config('constant.most_important_next_step_for_startup')[$startup->important_next_step] : $startup->other_important_next_step }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Website :</strong>
                                            <div class="font-normal">
                                                <p> {{ $startup->website ?? "-" }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Details</strong>
                                            <div class="location">
                                                <p>
                                                    {!! $startup->description ?? '-' !!}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Location </strong>
                                            <div class="location">
                                                <p>
                                                    {{ $startup->location ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="user-profile-wraper contact-details-wrap d-flex align-items-center row pl-3">
                                        <ul class="socials d-flex">
                                            @if(!empty($startup->fb_link))
                                            <li class="facebook">
                                                <a href="{{ $startup->fb_link }}" target="_blank" rel="noreferrer">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if(!empty($startup->insta_link))
                                            <li class="instagram">
                                                <a href="{{ $startup->insta_link }}" target="_blank" rel="noreferrer">
                                                    <i class="fa fa-instagram"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if(!empty($startup->tw_link))
                                            <li class="twitter">
                                                <a href="{{ $startup->tw_link }}" target="_blank" rel="noreferrer">
                                                    <i class="fa fa-twitter"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if(!empty($startup->linkedin_link))
                                            <li class="twitter">
                                                <a href="{{ $startup->linkedin_link }}" target="_blank"
                                                    rel="noreferrer">
                                                    <i class="fa fa-linkedin"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if(!empty($startup->tiktok_link))
                                            <li class="twitter">
                                                <a href="{{ $startup->tiktok_link }}" target="_blank" rel="noreferrer">
                                                    <i class="fa fa-github"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if(!empty($startup->website))
                                            <li class="web">
                                                <a href="{{ $startup->website }}" target="_blank" rel="noreferrer">
                                                    <i class="fa fa-external-link-square" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                    @if($startup->is_view > 0)
                                    <div class="form-group row mt-2">
                                        @if(isset($startup->business_plan) && $startup->business_plan != "")
                                        <div class="col-lg-4">
                                            <strong class="font-weight-bold label-before">Business Plan</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            <a href="{{ $exists_businessplan }}" target="_blank">Download Business
                                                Plan</a>
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </div>
                                    @if(isset($startup->financial) && $startup->financial != "")
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-4">
                                            <strong class="font-weight-bold label-before">Financial</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            <a href="{{ $exists_financial }}" target="_blank">Download Financial</a>
                                        </div>
                                    </div>
                                    @endif
                                    @if(isset($startup->pitch_deck) && $startup->pitch_deck != "")
                                    <div class="form-group row mt-2">
                                        <div class="col-lg-4">
                                            <strong class="font-weight-bold label-before">Pitch Deck</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            <a href="{{ $exists_pitch_deck }}" target="_blank">Download Pitch Deck</a>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endsection