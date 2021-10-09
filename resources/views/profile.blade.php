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
                @if(Auth::check() && Auth::id() == $profile->id)
                    <a href="{{ Auth::user()->type == config('constant.USER.TYPE.SIMPLE_USER') ? route('user.fill-profile') : route('entrepreneur.fill-profile') }}" class="btn edit-profile">Edit Profile</a>
                @endif
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
                            @isset($profile->userProfile->dob)
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-cake"></i></p>
                                    <p>{{ Carbon\Carbon::parse($profile->userProfile->dob)->format('jS F Y') }} </p>
                                </div>
                            </div>
                            @endisset
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
                            @if ($profile->userProfile->is_email_public > 0 || Auth::id() == $profile->id)
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-email"></i></p>
                                    <p>{{ $profile->email }} </p>
                                </div>
                            </div>
                            @endif
                            @isset($profile->userProfile->phone)
                            @if ($profile->userProfile->is_phone_public > 0 || Auth::id() == $profile->id)
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-phone-call"></i></p>
                                    <p>{{ $profile->userProfile->phone }}</p>
                                </div>
                            </div>
                            @endif
                            @endisset
                            @if ($profile->userProfile->is_resume_public > 0 || Auth::id() == $profile->id)
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center">
                                    <p><i class="mr-2 flaticon-file"></i></p>
                                    @php
                                        $cvUrl = Helper::images(config('constant.resume_url'));
                                        $cvUrl = $cvUrl . $profile->userProfile->resume;
                                    @endphp
                                    <p><a href="{{ $cvUrl }}">Download your CV</a></p>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="about-desc">
                            {!! $profile->userProfile->description !!}
                        </div>
                        <div class="contact-details-wrap d-flex align-items-center">
                            <ul class="contact-links">
                                {{--  <li>
                                    <a href="javascript:;" class="contact-link">Contact</a>
                                </li>  --}}
                                <li>
                                    <a href="{{ route('member.message', ['user'=> $profile->slug]) }}" class="contact-link">Message</a>
                                </li>
                                <li>
                                    <a href="{{ route('appointment.index') }}" class="contact-link">Appointments</a>
                                </li>
                                @if(Auth::check() && Auth::id() == $profile->id)
                                    <li>
                                        <a href="{{ route('bank-account') }}" class="contact-link">Bank Account</a>
                                    </li>
                                @endif
                                @if(Auth::check() && Auth::id() != $profile->id)
                                    <li>
                                        <a href="javascript:;" class="contact-link" data-toggle="modal" data-target="#appointment">Request Appointment</a>
                                    </li>
                                @endif
                            </ul>
                            <ul class="socials d-flex">
                                @if(!empty($profile->userProfile->fb_link))
                                    <li class="facebook">
                                        <a href="{{ Helper::checkSecureUrl($profile->userProfile->fb_link) }}" target="_blank"  rel="noreferrer">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(!empty($profile->userProfile->insta_link))
                                    <li class="instagram">
                                        <a href="{{ Helper::checkSecureUrl($profile->userProfile->insta_link) }}" target="_blank"  rel="noreferrer">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(!empty($profile->userProfile->tw_link))
                                    <li class="twitter">
                                        <a href="{{ Helper::checkSecureUrl($profile->userProfile->tw_link) }}" target="_blank"  rel="noreferrer">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(!empty($profile->userProfile->linkedin_link))
                                <li class="twitter">
                                    <a href="{{ Helper::checkSecureUrl($profile->userProfile->linkedin_link) }}" target="_blank" rel="noreferrer">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                                @endif
                                @if(!empty($profile->userProfile->github_link))
                                <li class="twitter">
                                    <a href="{{ Helper::checkSecureUrl($profile->userProfile->github_link) }}" target="_blank"  rel="noreferrer">
                                        <i class="fa fa-github"></i>
                                    </a>
                                </li>
                                @endif
                                @if(!empty($profile->userProfile->web_link))
                                    <li class="web">
                                        <a href="{{ Helper::checkSecureUrl($profile->userProfile->web_link) }}" target="_blank"  rel="noreferrer">
                                            <i class="fa fa-external-link-square" aria-hidden="true"></i>
                                        </a>
                                    </li>
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
                                            <div class="inner-text font-gray">{{ $work->responsibilities ?? "-" }}</div>
                                            <div class="inner-text font-gray">{{ $work->year }}- Year</div>
                                        </li>
                                    @endforeach
                                @endif
                                @if(!$is_experience)
                                    <li><div class="title-text">No experience</div></li>
                                @endif
                            </ul>
                            <ul class="timeline tab-pane fade" id="education">
                                @if(isset($EducationDetail) && !empty($EducationDetail) && $EducationDetail->count())
                                    @foreach($EducationDetail as $education)    
                                        <li>
                                            <div class="title-text">{{ $education->course_name }}</div>
                                            <div class="inner-text font-gray">{{ $education->organization_name }}</div>
                                            <div class="inner-text font-gray">{{ $education->major ?? "-" }}</div>
                                            <div class="inner-text font-gray">{{ $education->minor ?? "-" }}</div>
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
                    <input type="hidden" name="receiver_id" value="{{ $profile->id }}">
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