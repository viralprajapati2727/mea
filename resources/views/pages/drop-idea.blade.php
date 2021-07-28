@extends('layouts.app')
@section('content')
<div class="container">
    <div class="user_profile_form_page fill-idea">
        <div class="d-md-flex justify-content-center">
            <div class="col-md-9 p-0">
                <h2>Drop Your  Idea / Company</h2>
            </div>
        </div>
        <form class="idea-form" action="{{ route('idea.send-idea') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row mt-md-2 justify-content-center">
                <div class="col-9">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">First Name <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{ old('first_name') }}" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Last Name <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="{{ old('last_name') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Company / Idea Name <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company / Idea name" value="{{ old('company_name') }}" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">City You Live In <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="City You Live In" value="{{ old('city') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Century You Live In <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="century" id="century" placeholder="Century You Live In" value="{{ old('century') }}" >
                            </div>
                        </div> --}}
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Phone Number <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Occupation<span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Occupation" value="{{ old('occupation') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Email <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">How Old Are You ?<span class="required-star-color">*</span></label>
                                <input type="number" class="form-control" name="age" id="age" placeholder="How Old Are You" value="{{ old('age') }}" min="10" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label">Gender <span class="required-star-color">*</span></label>
                                <input type="radio" class="gender" name="gender" value="male" checked="">Male
                                <input type="radio" class="gender" name="gender" value="female" checked="">Female
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Company / Idea Description <span class="required-star-color">*</span></label>
                                <textarea name="description" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-paper-plane mr-2 submit-icon"></i>SEND</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('footer_script')
<script>
    
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/idea.js') }}"></script>
@endsection
