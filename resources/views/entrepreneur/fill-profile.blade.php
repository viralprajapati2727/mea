@extends('layouts.app')
@section('content')
@php
    $is_same_profile_photo = false;
    $ProfileUrl = Helper::images(config('constant.profile_url'));
    $exp_count = $edu_count  =  $is_profile = $is_experience = 0;

    $skills = $interests = "";
    $answers = [];
    if($profile->is_profile_filled == 1){
        $is_profile = 1;
        $is_experience = $profile->userProfile->is_experience;
        $skillsArr = $profile->skills->pluck('title')->toArray();
        $skills = implode(', ',$skillsArr);

        $interestsArr = $profile->interests->pluck('title')->toArray();
        $interests = implode(', ',$interestsArr);

        $answers = $profile->answers->pluck('title','question_id')->toArray();
        // echo "<pre>"; print_r($answersArr); echo "</pre>";

        $WorkExperience = $profile->workExperience;
        $EducationDetail = $profile->educationDetails;
    }
@endphp
    <div class="container">
        <div class="user_profile_form_page fill-profile">
            <div class="d-md-flex">
                <div class="col-md-12 p-0">
                    <p class="h5">Fill your profile detail and help us personalize your platform experience!</p>
                </div>
            </div>
            <form class="entrepreneur_profile_form " action="{{ route('entrepreneur.store-profile') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
                @method('POST')
                @csrf
                <div class="row mt-md-0 mt-3 pb-0">
                    <div class="col-lg-9 col-md-12">
                        @php $img_url = $ProfileUrl.'default.png'; @endphp
                        @if($profile->logo != "")
                            @php
                                if($profile->logo != "")
                                    $is_same_profile_photo = true;

                                $img_url = (isset($profile->logo) && $profile->logo != '') ? $ProfileUrl . $profile->logo : $ProfileUrl.'default.png';
                            @endphp
                            <input type="hidden" name="old_logo" value="{{ $profile->logo }}">
                        @endif
                        <div class="form-group account-img-content text-center text-md-left pb-2">
                            <div class="card-img-actions d-inline-block mt-2">
                                <img src="{{ $img_url }}" class="img-fluid rounded-circle account-img" alt="Photo">
                                <a href="javascript:void(0)" class="btn-upload d-flex justify-content-center align-items-center position-absolute" id="profile-photo-add-btn">
                                    <i class="flaticon-camera"></i>
                                    <input type="file" name="profile_image" class="profile_image custom-file-input w-100" data-type='image' id="profile-photo-add">
                                    <input type="hidden" name="old_profile_image" class="old_profile_image">
                                </a>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ old('name', isset($profile->name) ? $profile->name:'' ) }}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your Email" value="{{ old('email', isset($profile->email) ? $profile->email : '' ) }}"  disabled readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Gender </label>
                                    <select name="gender" id="gender" class="form-control select2 no-search-select2" data-placeholder="Select Gender">
                                        <option></option>
                                        <option value="Male" {{ (old("gender") == 'Male' || (isset($profile->userProfile->gender) && $profile->userProfile->gender == 'Male')) ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ (old("gender") == 'Female' || (isset($profile->userProfile->gender) && $profile->userProfile->gender == 'Female')) ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label class="form-control-label">Date of Birth</label>
                                    <input type="text" class="form-control birthdate" name="dob" id="dob" placeholder="Select Your Date of Birth" value="{{ old('dob', isset($profile->userProfile->dob)? (\Carbon\Carbon::createFromFormat('Y-m-d',$profile->userProfile->dob)->format('d/m/Y')):'' ) }}" >
                                    <div class="date-of-birth-icon">
                                        <i class="flaticon-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">City</label>
                                    <input type="text" class="form-control" name="city" id="search_city" placeholder="Enter City" value="{{ old('city', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Contact Number</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Your Contact Number" value="{{ old('phone', isset($profile->userProfile->phone) ? $profile->userProfile->phone:'' ) }}" maxlength="15" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Website Link </label>
                                    <input type="text" class="form-control" name="web_link" id="web_link" placeholder="Enter Your Website Link" value="{{ old('web_link', isset($profile->userProfile->web_link)?$profile->userProfile->web_link:'' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Facebook Link</label>
                                    <input type="text" class="form-control" name="fb_link" id="fb_link" placeholder="Enter Facebook Link" value="{{ old('fb_link', isset($profile->userProfile->fb_link)?$profile->userProfile->fb_link:'' ) }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Instagram Link</label>
                                    <input type="text" class="form-control" name="insta_link" id="insta_link" placeholder="Enter Instagram Link" value="{{ old('insta_link', isset($profile->userProfile->insta_link)?$profile->userProfile->insta_link:'' ) }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Twitter Link</label>
                                    <input type="text" class="form-control" name="tw_link" id="tw_link" placeholder="Enter Twitter Link" value="{{ old('tw_link', isset($profile->userProfile->tw_link)?$profile->userProfile->tw_link:'' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">About Myself</label>
                                    <textarea name="about" id="about" rows="5" class="form-control" placeholder="Brief Your Skills Here">{{ old('about', isset($profile->userProfile->description)?$profile->userProfile->description:'' ) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Skills</label>
                                    <input type="text" name="skills" id="skills" class="form-control tokenfield" value="{{ $skills }}" data-fouc>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Interests</label>
                                    <input type="text" name="interests" id="interests" class="form-control tokenfield" value="{{ $interests }}" data-fouc>
                                </div>
                            </div>
                        </div>
                        @if(!empty($questions))
                        <div class="profile-inner">
                            <div class="row mt-md-2">
                                <div class="col-12">
                                    <h2>Suevey Questions</h2>
                                </div>
                                <div class="col-12 questions">
                                    @forelse ($questions as $key => $question)
                                    @php
                                        $answer = "";
                                        if(!empty($answers) && array_key_exists($question->id, $answers)){
                                            $answer = $answers[$question->id];
                                        }
                                    @endphp
                                        <div class="form-group">
                                            <p>{{ $question->title }}</p>
                                            <input type="text" class="form-control answer" name="ans[{{ $question->id }}]" id="ans{{ $question->id }}" placeholder="Enter Answer" value="{{ $answer }}" >
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        @endif
                        <div class="profile-inner">
                            <div class="row mt-md-2">
                                <div class="col-12">
                                    <h2>Work Experience</h2>
                                </div>
                                <div class="col-lg-9 col-md-12" id="work-eperieance">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="is_experience" id="is_experience" class="form-check-input-styled" data-fouc="" value="1" {{ $is_experience == 0 ? 'checked' : '' }}>I have no experience
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row or_add">
                                        <div class="col-md-12 mt-2 mb-2">
                                            <b>OR</b>
                                        </div>
                                    </div>
                                    @php $exp_count = 0; @endphp
                                    <div class="work-exp-details">
                                        @if(isset($WorkExperience) && !empty($WorkExperience) && $WorkExperience->count())
                                            @php $exp_total = count($WorkExperience) - 1; @endphp
                                            @foreach($WorkExperience as $work)
                                                <div class="work-exp-item">
                                                    <div class="d-flex align-items-center">
                                                        <a href="javascript:;" class="ml-auto delete-work-exp"><i class="icon-cross2"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Company Name <span class="font-color">*</span></label>
                                                                <input type="text" name="exp[{{ $exp_count }}][company_name]" placeholder="Company Name" class="form-control company_name" value="{{ ($is_profile) ? $work->company_name : "" }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Designation <span class="font-color">*</span></label>
                                                                <input type="text" name="exp[{{ $exp_count }}][designation]" placeholder="Designation" class="form-control designation" value="{{ ($is_profile) ? $work->designation : "" }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Year <span class="font-color">*</span></label>
                                                                <input type="number" name="exp[{{ $exp_count }}][year]" placeholder="Year" class="form-control year" value="{{ ($is_profile) ? $work->year : "" }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="exp[{{ $exp_count }}][work_id]" class="id" value="{{ $work->id }}">
                                                </div>
                                                @php $exp_count++; @endphp
                                            @endforeach
                                        @else
                                            <div class="work-exp-item">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:;" class="ml-auto delete-work-exp"><i class="icon-cross2"></i></a>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Company Name <span class="font-color">*</span></label>
                                                            <input type="text" name="exp[{{ $exp_count }}][company_name]" placeholder="Company Name" class="form-control company_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Designation <span class="font-color">*</span></label>
                                                            <input type="text" name="exp[{{ $exp_count }}][designation]" placeholder="Designation" class="form-control designation">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Year <span class="font-color">*</span></label>
                                                            <input type="number" name="exp[{{ $exp_count }}][year]" placeholder="Year" class="form-control year">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-check form-check-inline">
                                            <button type="button" class="btn btn-primary btn-sm btn-add-more-exp"><i class="icon-plus2"></i> Add More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-inner">
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <h2>Education Details</h2>
                            </div>
                            <div class="col-lg-9 col-md-12 mt-2" id="education-details">
                                @php $edu_count = 0; @endphp
                                <div class="education-details">
                                    @if(isset($EducationDetail) && !empty($EducationDetail))
                                        @php $edu_total = count($EducationDetail) - 1; @endphp
                                        @foreach($EducationDetail as $education)
                                            <div class="education-item">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:;" class="ml-auto delete-edu-exp"><i class="icon-cross2"></i></a>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Course Name <span class="font-color">*</span></label>
                                                            <input type="text" name="edu[{{ $edu_count }}][course_name]" placeholder="Course Name" class="form-control course_name" value="{{ ($is_profile) ? $education->course_name : "" }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">College Name / Organization Name <span class="font-color">*</span></label>
                                                            <input type="text" name="edu[{{ $edu_count }}][organization_name]" placeholder="College Name / Organization Name" class="form-control organization_name" value="{{ ($is_profile) ? $education->organization_name : "" }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Grade <span class="font-color">*</span></label>
                                                            <input type="text" name="edu[{{ $edu_count }}][percentage]" placeholder="Grade" class="form-control percentage" value="{{ ($is_profile) ? $education->percentage : "" }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Year <span class="font-color">*</span></label>
                                                            <input type="text" name="edu[{{ $edu_count }}][year]" placeholder="Year" class="form-control year" value="{{ ($is_profile) ? $education->year : "" }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @php $edu_count++; @endphp
                                        @endforeach
                                    @else
                                        <div class="education-item">
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" class="ml-auto delete-edu-exp"><i class="icon-cross2"></i></a>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Course Name <span class="font-color">*</span></label>
                                                        <input type="text" name="edu[{{ $edu_count }}][course_name]" placeholder="Course Name" class="form-control course_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-form-label">College Name / Organization Name <span class="font-color">*</span></label>
                                                        <input type="text" name="edu[{{ $edu_count }}][organization_name]" placeholder="College Name / Organization Name" class="form-control organization_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Grade <span class="font-color">*</span></label>
                                                        <input type="text" name="edu[{{ $edu_count }}][percentage]" placeholder="Grade" class="form-control percentage">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Year <span class="font-color">*</span></label>
                                                        <input type="text" name="edu[{{ $edu_count }}][year]" placeholder="Year" class="form-control year">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <button type="button" class="btn btn-primary btn-sm btn-add-more-edu"><i class="icon-plus2"></i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                            <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>SAVE</button>
                            @if(isset($profile->is_profile_filled) && $profile->is_profile_filled == 1)
                                <span class="pl-3 d-md-inline-block d-lg-inline-block pt-4 pt-md-0 pt-lg-0 text-center"><a href="{{route('index')}}" class="text-common-color font-semi-bold entry-cancel">CANCEL</a></span>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}"></script>
<script>
    var is_form_edit = false;
    var ex_count = parseInt("{{ $exp_count }}");
    var ed_count = parseInt("{{ $edu_count }}");
    var is_experience = "{{ $is_experience }}";
    var is_profile_exists = {!! $is_same_profile_photo ? 'false' : 'true' !!};
    var is_profile_filled = '@if(isset($profile->is_profile_filled) && $profile->is_profile_filled == 1) {{1}} @else {{0}} @endif';
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/entrepreneur_profile_form.js') }}"></script>
@endsection
