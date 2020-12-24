@extends('layouts.app')
@section('content')
@php
    $is_experience = $profile->userProfile->is_experience;
    $WorkExperience = collect($profile->workExperience)->sortByDesc(['id']);
    $EducationDetail = collect($profile->educationDetails)->sortByDesc(['id']);
@endphp
<div class="page-main">
    <div class="user-profile-wraper">
        <div class="container">
            <div class="user-title-wrap">
                <h2 class="title">{{ $profile->name }}</h2>
                <a href="{{ Auth::user()->type == config('constant.USER.TYPE.SIMPLE_USER') ? route('user.fill-profile') : route('entrepreneur.fill-profile') }}" class="btn edit-profile">Edit Profile</a>
            </div>
            <div class="profile-top-detial">
                <div class="banner">
                    @php
                        $ProfileCoverUrl = Helper::images(config('constant.profile_cover_url'));
                        $ProfileCoverUrl = (isset($profile->userProfile->cover) && $profile->userProfile->cover != '') ? $ProfileCoverUrl . $profile->userProfile->cover : $ProfileCoverUrl.'default.jpg';
                    @endphp
                    <img src="{{ $ProfileCoverUrl }}" alt="" class="w-100">
                </div>
                <div class="personal-details d-flex">
                    <div class="profile-image">
                        @php
                            $ProfileUrl = Helper::images(config('constant.profile_url'));
                            $img_url = (isset($profile->logo) && $profile->logo != '') ? $ProfileUrl . $profile->logo : $ProfileUrl.'default.png';
                        @endphp
                        <img src="{{ $img_url }}" alt="">
                    </div>
                    <div class="user-detials">
                        <h2>{{ $profile->name }}</h2>
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-cake"></i></p>
                                    <p>{{ Carbon\Carbon::parse($profile->userProfile->dob)->format('jS F Y') }} </p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-placeholder"></i></p>
                                    <p>{{ $profile->userProfile->city }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-gender-equality"></i></p>
                                    <p>{{ $profile->userProfile->gender }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-email"></i></p>
                                    <p>{{ $profile->email }} </p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-phone-call"></i></p>
                                    <p>{{ $profile->userProfile->phone }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-phone-call"></i></p>
                                    @php
                                        $cvUrl = Helper::images(config('constant.resume_url'));
                                        $cvUrl = $cvUrl . $profile->userProfile->resume;
                                    @endphp
                                    <p><a href="{{ $cvUrl }}">Download your CV</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="about-desc">
                            {!! $profile->userProfile->description !!}
                        </div>
                        <div class="contact-details-wrap d-flex align-items-center">
                            <ul class="contact-links">
                                <li>
                                    <a href="javascript:;" class="contact-link">Contact</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Message</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Appointment</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Request Appointment</a>
                                </li>
                            </ul>
                            <ul class="socials d-flex">
                                @if(!empty($profile->userProfile->fb_link))
                                    <li class="facebook"><a href="{{ $profile->userProfile->fb_link }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                @endif
                                @if(!empty($profile->userProfile->insta_link))
                                    <li class="instagram"><a href="{{ $profile->userProfile->insta_link }}" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                @endif
                                @if(!empty($profile->userProfile->tw_link))
                                    <li class="twitter"><a href="{{ $profile->userProfile->tw_link }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                @endif
                                @if(!empty($profile->userProfile->web_link))
                                    <li class="web"><a href="{{ $profile->userProfile->web_link }}" target="_blank"><i class="fa fa-external-link-square" aria-hidden="true"></i></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-advance-details">
                <div class="skills">
                    <h3>{{ $profile->name }}'s Skills</h3>
                    <ul class="d-flex">
                        @forelse($profile->skills as $key => $skill)
                            <li>{{ $skill->title }}</li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div class="intersts">
                    <h3>{{ $profile->name }}'s Interests</h3>
                    <ul class="d-flex">
                        @forelse($profile->interests as $key => $interest)
                            <li>{{ $interest->title }}</li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                @if(!empty($questions))
                <div class="user-question-answer-wrap">
                    <div class="user-questions">
                        <h3>{{ $profile->name }}'s Answers</h3>
                        <ul class="question-list">
                            @php $answers = $profile->answers->pluck('title','question_id')->toArray(); @endphp
                            @forelse ($questions as $key => $question)
                                @php
                                    $answer = "";
                                    if(!empty($answers) && array_key_exists($question->id, $answers)){
                                        $answer = $answers[$question->id];
                                    }
                                @endphp
                                    <li><p>Q. {{ $question->title }}</p><span>Ans : {{ $answer }}</span></li>
                                @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            @if($profile->type == config('constant.USER.TYPE.ENTREPRENEUR'))
                <div class="profile-education-details">
                    <div class="inner-wrap">
                        <ul class="nav nav-tabs nav-tabs-bottom candidate-profile-tab w-100">
                            <li class="nav-item"><a href="#experience" class="nav-link active show" data-toggle="tab">Work EXPERIENCE</a></li>
                            <li class="nav-item ml-2 ml-lg-4 ml-sm-3"><a href="#education" class="nav-link" data-toggle="tab">EDUCATION</a></li>
                        </ul>
                        <div class="tab-content">
                            <ul class="timeline tab-pane fade active show" id="experience">
                                @if(isset($WorkExperience) && !empty($WorkExperience) && $WorkExperience->count())
                                    @foreach($WorkExperience as $work)
                                        <li>
                                            <div class="title-text">{{ $work->company_name }}</div>
                                            <div class="inner-text font-gray">{{ $work->designation }}</div>
                                            <div class="inner-text font-gray">{{ $work->year }}- Year</div>
                                        </li>
                                    @endforeach
                                @endif
                                @if($is_experience)
                                    <li><div class="title-text">No experience</div></li>
                                @endif
                            </ul>
                            <ul class="timeline tab-pane fade" id="education">
                                @if(isset($EducationDetail) && !empty($EducationDetail) && $EducationDetail->count())
                                    @foreach($EducationDetail as $education)    
                                        <li>
                                            <div class="title-text">{{ $education->course_name }}</div>
                                            <div class="inner-text font-gray">{{ $education->organization_name }}</div>
                                            <div class="inner-text font-gray">{{ $education->percentage }}</div>
                                            <div class="inner-text font-gray">{{ $education->year }} Year</div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
</div>
@endsection