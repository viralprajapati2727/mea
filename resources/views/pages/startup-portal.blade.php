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
                                    <h3>We review your financial and help with projections</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-cloud"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We review your technology and assist with any updates needed</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-deal"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We setup an initial meeting</h3>
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
                                    <h3>We evaluate your startup for capital investment opportunities</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="process-bottom">
                    <p>A successful startup begins with a roadmap, vision statement and scalability plan. We help evaluate your business on what it needs to help it take it to the next level and raise capital from our team of investors!</p>
                </div>
            </div>
        </div>
        <div class="building-plan">
            <div class="container">
                <div class="title">
                    <h2>Building Your Plan</h2>
                </div>
                <p>We know the metrics, research and due diligence required to raise funds. It can seem like a daunting task to put together however our team of experts have years of experience across different industries. We will work with you to take your startup to the next level and become a game changer! </p>
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
                            {{-- <a href="{{ route('startup-portal') }}">Click Here</a> --}}
                            <a href="javascript:;" data-toggle="modal" data-target="#appointment">Click Here</a>
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
                    <h2>Browse Startup Plan/Idea
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
                                    <h2 class="name">
                                        <a href="{{ route('start-statup-portal',['action'=>'view','portal_id' => $startup->id]) }}"> 
                                            {{ $startup->name }}
                                        </a>
                                    </h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Industry :</strong>
                                            <div class="skills">
                                                <p>{{ $startup->industry ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Owner : </strong>
                                            <div class="location">
                                                <a
                                                href="{{ route('user.view-profile', ['slug' => $startup->user['slug'] ]) }}">
                                                    {{ $startup->user['name'] }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Location :</strong>
                                            <div class="location">
                                                <p>
                                                    {{ $startup->location ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="user-profile-wraper contact-details-wrap d-flex align-items-center row pl-3">
                                                <ul class="socials d-flex">
                                                    @if(!empty($startup->fb_link))
                                                    <li class="facebook">
                                                        <a href="{{ $startup->fb_link }}" target="_blank"
                                                            rel="noreferrer">
                                                            <i class="fa fa-facebook"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(!empty($startup->insta_link))
                                                    <li class="instagram">
                                                        <a href="{{ $startup->insta_link }}" target="_blank"
                                                            rel="noreferrer">
                                                            <i class="fa fa-instagram"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(!empty($startup->tw_link))
                                                    <li class="twitter">
                                                        <a href="{{ $startup->tw_link }}" target="_blank"
                                                            rel="noreferrer">
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
                                                        <a href="{{ $startup->tiktok_link }}" target="_blank"
                                                            rel="noreferrer">
                                                            <i class="fa fa-github"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(!empty($startup->website))
                                                    <li class="web">
                                                        <a href="{{ $startup->website }}" target="_blank"
                                                            rel="noreferrer">
                                                            <i class="fa fa-external-link-square"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @if($startup->is_view > 0)
                                            <div class="form-group row mt-2">
                                                @if(isset($startup->business_plan) && $startup->business_plan != "")
                                                <div class="col-lg-4">
                                                    <strong class="font-weight-bold label-before">Business Plan :</strong>
                                                </div>
                                                <div class="col-lg-8">
                                                    <a href="{{ $exists_businessplan }}" target="_blank">
                                                        Download Business Plan
                                                    </a>
                                                </div>
                                                @else
                                                -
                                                @endif
                                            </div>
                                            {{-- @if(isset($startup->financial) && $startup->financial != "")
                                            <div class="form-group row mt-2">
                                                <div class="col-lg-4">
                                                    <strong class="font-weight-bold label-before">Financial</strong>
                                                </div>
                                                <div class="col-lg-8">
                                                    <a href="{{ $exists_financial }}" target="_blank">Download
                                                        Financial</a>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($startup->pitch_deck) && $startup->pitch_deck != "")
                                            <div class="form-group row mt-2">
                                                <div class="col-lg-4">
                                                    <strong class="font-weight-bold label-before">Pitch Deck</strong>
                                                </div>
                                                <div class="col-lg-8">
                                                    <a href="{{ $exists_pitch_deck }}" target="_blank">Download Pitch
                                                        Deck</a>
                                                </div>
                                            </div>
                                            @endif --}}
                                            @endif
                                        </div>
                                    </div>
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
    
<!-- appoinment modal -->
<div id="appointment" class="modal fade" tabindex="-1" style="z-index: 99999">
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
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ Auth::user()->name ?? ''  }}" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your Email" value="{{  Auth::user()->email ?? '' }}"  disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-md-2">
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label class="form-control-label">Date <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control birthdate" name="date" id="date" placeholder="Select Date of Appointment" value="" >
                                <div class="date-of-birth-icon">
                                    {{-- <i class="flaticon-calendar"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label class="form-control-label">Time <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control appointment_time" name="appointment_time" id="appointment_time" placeholder="Select Time of Appointment" value="" >
                                <div class="date-of-birth-icon">
                                    <i class="flaticon-clock"></i>
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