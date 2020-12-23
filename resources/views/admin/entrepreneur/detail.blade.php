@extends('admin.app-admin')
@section('title') Entrepreneur Management @endsection
@section('page-header')
    @php
    $is_experience = $profile->userProfile->is_experience;
    $WorkExperience = collect($profile->workExperience)->sortByDesc(['id']);
    $EducationDetail = collect($profile->educationDetails)->sortByDesc(['id']);

    $resumeUrl = Helper::images(config('constant.resume_url'));
    $exists_resume = "";
    if($profile->userProfile->resume != ""){
        $is_same_resume = true;
        $exists_resume = $resumeUrl.$profile->userProfile->resume;
    }
    @endphp
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('admin.entrepreneur.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Entrepreneur</a>
                    <span class="breadcrumb-item active">Entrepreneur Listing</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <h6 class="card-title text-center">Entrepreneur Details</h6>
    <div class="card">
        <div class="card-body custom-tabs">
            <div class="row">
                <div class="col-md-8">
                    <div class="detail-section">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Name</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $profile->name }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Email</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $profile->email }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">City</label>
                            </div>
                            <div class="col-lg-4">
                                <p class="font-weight-bold">{{ $profile->userProfile->city }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Date of Birth</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ Carbon\Carbon::parse($profile->userProfile->dob)->format('jS F Y') }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Gender</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{{ $profile->userProfile->gender }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Contact Number</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold"><a href="tel:{{ $profile->userProfile->phone }}">{{ $profile->userProfile->phone }}</a></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">About</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold">{!! $profile->userProfile->description !!}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Updated CV</label>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-weight-bold"><a href="{{ $exists_resume }}" target="_blank">Download Updated CV</a></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Skills</label>
                            </div>
                            <div class="col-lg-4">
                                @foreach($profile->skills as $skill)
                                    <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($skill->title) ? $skill->title : "" }}</p>
                                @endforeach
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold label-before">Interests</label>
                            </div>
                            <div class="col-lg-4">
                                @foreach($profile->interests as $interest)
                                    <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($interest->title) ? $interest->title : "" }}</p>
                                @endforeach
                            </div>
                        </div>
                        @if(!empty($profile->userProfile->fb_link))
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="font-weight-bold label-before">Facebook</label>
                                </div>
                                <div class="col-lg-8">
                                    <p class="font-weight-bold">
                                        <a target="_blank" href="{{ $profile->userProfile->fb_link }}">{{ isset($profile->userProfile->fb_link) ? $profile->userProfile->fb_link  : " - "}}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($profile->userProfile->insta_link))
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="font-weight-bold label-before">Instagram</label>
                                </div>
                                <div class="col-lg-8">
                                    <p class="font-weight-bold">
                                        <a target="_blank" href="{{ $profile->userProfile->insta_link }}">{{ isset($profile->userProfile->insta_link) ? $profile->userProfile->insta_link  : " - "}}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($profile->userProfile->tw_link))
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="font-weight-bold label-before">Twitter</label>
                                </div>
                                <div class="col-lg-8">
                                    <p class="font-weight-bold">
                                        <a target="_blank" href="{{ $profile->userProfile->tw_link }}">{{ isset($profile->userProfile->tw_link) ? $profile->userProfile->tw_link  : " - "}}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($profile->userProfile->web_link))
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="font-weight-bold label-before">Website</label>
                                </div>
                                <div class="col-lg-8">
                                    <p class="font-weight-bold">
                                        <a target="_blank" href="{{ $profile->userProfile->web_link }}">{{ isset($profile->userProfile->web_link) ? $profile->userProfile->web_link  : " - "}}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if(!empty($questions))
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Survey Questions</label>
                        </div>
                        <div class="col-lg-8">
                            <ul class="question-list p-0">
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
                    @if($profile->type == config('constant.USER.TYPE.ENTREPRENEUR'))
                        <div class="col-12">
                            <div class="row">
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
                <div class="col-md-4">
                    <div class="col-md-12 py-0">
                        <div class="row">
                            <div class="col-lg-3 pr-0">
                                <label class="font-weight-bold">Status : </label>
                            </div>
                            <div class="col-lg-8 pl-0">
                                @php
                                    $status = array_search($profile->is_active,config('constant.USER.STATUS'));
                                    $statusArr = [0 => 'info', 1 => 'success', 2 => 'danger'];
                                    $text = "<span class='custom-badge badge badge-".$statusArr[$profile->is_active]."'>".$status."</span>";
                                @endphp
                                {!! $text !!}
                            </div>
                        </div>
                    </div>
                    @php
                        $ProfileUrl = Helper::images(config('constant.profile_url'));
                        $img_url = (isset($profile->logo) && $profile->logo != '') ? $ProfileUrl . $profile->logo : $ProfileUrl.'default.png';
                    @endphp
                    
                    <a href="{{ $img_url }}" class="fancy-pop-image" data-fancybox="images">
                        <img class="users_logo" src="{{ $img_url }}" alt="">
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_content')

@endsection
