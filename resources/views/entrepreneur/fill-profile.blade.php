@extends('layouts.app')
@section('content')
@php
    $ProfileUrl = Helper::images(config('constant.profile_url'));
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
                        <div class="account-img-content text-center text-md-left">
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
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your Email" value="{{ old('email', isset($profile->email) ? $profile->email:'' ) }}"  disabled readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Gender </label>
                                    <select name="gender" id="gender" class="form-control select2 no-search-select2" data-placeholder="Select Gender">
                                        <option></option>
                                        <option value="1" {{ (old("gender") == 1 || (isset($profile->userProfile->gender) && $profile->userProfile->gender == 'Male')) ? 'selected' : '' }}>Male</option>
                                        <option value="2" {{ (old("gender") == 2 || (isset($profile->userProfile->gender) && $profile->userProfile->gender == 'Female')) ? 'selected' : '' }}>Female</option>
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
                                    <input type="text" name="skills" id="skills" class="form-control tokenfield" placeholder="Add skill" value="Finance Strategy,Product Management,Technical Programming" data-fouc>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Interests</label>
                                    <input type="text" name="interests" id="interests" class="form-control tokenfield" placeholder="Add interest" value="CEO,Business Development" data-fouc>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <h2>Suevey Questions</h2>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <p>What question would it be and why if..?</p>
                                    <input type="text" class="form-control" name="ans[1]" id="ans1" placeholder="Enter Answer" value="" >
                                </div>
                                <div class="form-group">
                                    <p>Discuss your business idea here!</p>
                                    <input type="text" class="form-control" name="ans[1]" id="ans1" placeholder="Enter Answer" value="" >
                                </div>
                                <div class="form-group">
                                    <p>Application has been rejected or not...?</p>
                                    <input type="text" class="form-control" name="ans[1]" id="ans1" placeholder="Enter Answer" value="" >
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left">
                            <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>SAVE
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
<script type="text/javascript" src="{{ Helper::assets('js/pages/entrepreneur_profile_form.js') }}"></script>
@endsection