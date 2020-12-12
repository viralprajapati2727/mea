@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="user_profile_form_page fill-profile">
            <div class="d-md-flex">
                <div class="col-md-12 p-0">
                    <h2>Post a Job</h2>
                </div>
            </div>
            <form class="post-job-form " action="{{ route('job.update-job') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
                @method('POST')
                @csrf
                <div class="row mt-md-0 mt-3 pb-0">
                    <div class="col-lg-9 col-md-12">
                        <div class="row mt-md-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Job Title</label>
                                    <select name="job_title_id" id="job_title_id" class="form-control select2 no-search-select2" data-placeholder="Select Job Title">
                                        <option></option>
                                        @forelse ($jobtitles as $jobtitle)
                                            <option value="{{ $jobtitle->id }}">{{ $jobtitle->title }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Job Type</label>
                                    <select name="job_type_id" id="job_type_id" class="form-control select2 no-search-select2" data-placeholder="Select Job Type">
                                        <option></option>
                                        @forelse (config('constant.job_type') as $key => $jobtype)
                                            <option value="{{ $key }}">{{ $jobtype }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-12">
                                <h5>Salary Range</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Currency </label>
                                    <select name="currency_id" id="currency_id" class="form-control select2 no-search-select2" data-placeholder="Select Currency">
                                        <option></option>
                                        @forelse($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->code .' ('.$currency->symbol.') ' }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Min</label>
                                    <input type="text" class="form-control" name="min_salary" id="min_salary" placeholder="Min Salary" value="{{ old('min_salary', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Max</label>
                                    <input type="text" class="form-control" name="max_salary" id="max_salary" placeholder="Max Salary" value="{{ old('max_salary', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-12">
                                <h5>Job Timing</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Start Time</label>
                                    <input type="text" class="form-control" name="job_start_time" id="job_start_time" placeholder="Start Time" value="{{ old('job_start_time', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">End Time</label>
                                    <input type="text" class="form-control" name="job_end_time" id="job_end_time" placeholder="End Time" value="{{ old('job_end_time', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Job Location</label>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="Job Location" value="{{ old('location', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Required Experience(In Year)</label>
                                    <input type="text" class="form-control" name="required_experience" id="required_experience" placeholder="Year" value="{{ old('required_experience', isset($profile->userProfile->city) ? $profile->userProfile->city : '' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Key skills</label>
                                    {{-- <a href="javascript:;"><i class="fa fa-info-circle ml-1" aria-hidden="true" data-popup="popover" title="" data-trigger="hover" data-html="true" data-content="<p dir='ltr' style='text-align:left'>Start adding multiple key skills separated by comma.</p>"></i></a> --}}
                                    <input type="text" name="key_skills" id="key_skills" class="form-control tokenfield" value="" data-fouc>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Job Description</label>
                                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Job Description">{{ old('description', isset($profile->userProfile->description)?$profile->userProfile->description:'' ) }}</textarea>
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
    
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/post_job.js') }}"></script>
@endsection
