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
                                    <i class="flaticon-checklist"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We review your Business Plan</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="process-bottom">
                    <p>A business begins with the plan and financial tools for success we help you shapw and define both. 
                    Our process appropriately situates an entrepreneur to <strong>define and namage the business pan</strong> and <strong>avoid the pitfalls</strong> of getting buried in the details.</p>
                </div>
            </div>
        </div>
        <div class="building-plan">
            <div class="container">
                <div class="title"><h2>Building Your Plan</h2></div>
                <p>The fear concern, time and resources needed for setting up astartup seems daunting at first glance - but it doesn't have to be! 
                    Startup Portal evolved from years of experience in capital raising cash management, financial reporting and managing the day to day.</p>
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
        @if(!$recentMembers->isEmpty() && $recentMembers->count())
            <div class="browse-plans">
                <div class="container">
                    <div class="title">
                        <h2>Browse Business Plan/Idea
                    </div>
                    <div class="member-list">
                        {{-- @forelse ($recentMembers as $member)
                            <div class="card">
                                <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                                    <div class="media-left">
                                        @php
                                            $ProfileUrl = Helper::images(config('constant.profile_url'));
                                            $img_url = (isset($member->logo) && $member->logo != '') ? $ProfileUrl . $member->logo : $ProfileUrl.'default.png';
                                        @endphp
                                        <a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}" class="profile-image">
                                            <img src="{{ $img_url }}" alt="" class="w-100">
                                        </a>
                                    </div>
                                    <div class="member-detail">
                                        <h2 class="name">{{ $member->name }}</h2>
                                        <div class="skills">
                                            <label>Skills</label>
                                            @if (sizeof($member->skills) > 0)
                                                @foreach ($member->skills as $skill)
                                                    <p>{{ $skill->title }}</p>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="location">
                                            <label>City</label>
                                            <p>{{ $member->userProfile ? $member->userProfile->city : '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="contact-details">
                                        <ul>
                                            <li><a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}">Contact</a></li>
                                            <li><a href="{{ route('member.message', ['user'=> $member->slug]) }}">Message</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse --}}
                        <div class="card">
                            <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                                <div class="media-left">
                                    @php
                                        $ProfileUrl = Helper::images(config('constant.profile_url'));
                                        $img_url = (isset($member) && $member->logo != '') ? $ProfileUrl . $member->logo : $ProfileUrl.'default.png';
                                    @endphp
                                    <a href="#" class="profile-image">
                                        <img src="{{ $img_url }}" alt="" class="w-100">
                                    </a>
                                </div>
                                <div class="member-detail">
                                    <h2 class="name">Rapportive</h2>
                                    <label>Industry :</label>
                                    <div class="skills">
                                        <p>IT</p>
                                    </div>
                                    <label>Stage of Startup :</label>
                                    <div class="skills">
                                        <p>Startup Operational (obtaining revenue)</p>
                                    </div>
                                    <label>What’s the most important next step for your startup? :</label>
                                    <div class="skills">
                                        <p> Build your product </p>
                                    </div>
                                    <label>Website :</label>
                                    <div class="skills">
                                        <p> - </p>
                                    </div>
                                    <label>Details</label>
                                    <div class="location">
                                        <p>
                                            Rapportive shows you everything about your contacts from inside your email inbox. A large screenshot placed on the left hand side of the home page allows you to easily see the product in action. A bright call-to-action inspires the visitor to take action and download the application.
                                        </p>
                                    </div>
                                </div>
                                {{-- <div class="contact-details">
                                    <ul>
                                        <li><a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}">Contact</a></li>
                                        <li><a href="{{ route('member.message', ['user'=> $member->slug]) }}">Message</a></li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection