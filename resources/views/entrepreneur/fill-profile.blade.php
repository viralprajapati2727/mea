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
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/pages/entrepreneur_profile_form.js') }}"></script>
@endsection
