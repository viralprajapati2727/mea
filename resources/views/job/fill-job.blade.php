@extends('layouts.app')
@section('content')
@php
    $is_job = false;
    $inserted_key_skills = [];
    if(isset($job) && !empty($job)){
        $is_job = true;

        $inserted_key_skills = $job->key_skills;
    }
@endphp
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
                                    <label class="form-control-label">Job Title <span class="required-star-color">*</span></label>
                                    <select name="job_title_id" id="job_title_id" class="form-control select2 no-search-select2" data-placeholder="Select Job Title">
                                        <option></option>
                                        @forelse ($jobtitles as $jobtitle)
                                            <option value="{{ $jobtitle->id }}" {{ ($is_job) ? ($job->job_title_id == $jobtitle->id ? 'selected' : ''): ''  }}>{{ $jobtitle->title }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Job Type <span class="required-star-color">*</span></label>
                                    <select name="job_type_id" id="job_type_id" class="form-control select2 no-search-select2" data-placeholder="Select Job Type">
                                        <option></option>
                                        @forelse (config('constant.job_type') as $key => $jobtype)
                                            <option value="{{ $key }}" {{ ($is_job) ? ($job->job_type_id == $key ? 'selected' : ''): ''  }}>{{ $jobtype }}</option>
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
                                    <label class="form-control-label">Currency <span class="required-star-color">*</span></label>
                                    <select name="currency_id" id="currency_id" class="form-control select2 no-search-select2" data-placeholder="Select Currency">
                                        <option></option>
                                        @forelse($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ ($is_job) ? ($job->currency_id == $currency->id ? 'selected' : ''): ''  }}>{{ $currency->code .' ('.$currency->symbol.') ' }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Min (Per Year) <span class="required-star-color">*</span></label>
                                    <input type="text" class="form-control min_salary" name="min_salary" id="min_salary" placeholder="Min Salary" value="{{ ($is_job) ? $job->min_salary : old('min_salary') }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Max( Per Year) <span class="required-star-color">*</span></label>
                                    <input type="text" class="form-control" name="max_salary" id="max_salary" placeholder="Max Salary" value="{{ ($is_job) ? $job->max_salary : old('max_salary') }}" >
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
                                    <input type="text" class="form-control job_start_time" name="job_start_time" id="job_start_time" placeholder="Start Time" value="{{ ($is_job) ? $job->job_start_time : old('job_start_time') }}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">End Time</label>
                                    <input type="text" class="form-control" name="job_end_time" id="job_end_time" placeholder="End Time" value="{{ ($is_job) ? $job->job_end_time : old('job_end_time') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Job Location <span class="required-star-color">*</span></label>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="Job Location" value="{{ ($is_job) ? $job->location : old('location') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Required Experience(In Year) <span class="required-star-color">*</span></label>
                                    <input type="text" class="form-control" name="required_experience" id="required_experience" placeholder="Year" value="{{ $is_job ? $job->required_experience : old('required_experience') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Key skills <span class="required-star-color">*</span></label>
                                    {{-- <a href="javascript:;"><i class="fa fa-info-circle ml-1" aria-hidden="true" data-popup="popover" title="" data-trigger="hover" data-html="true" data-content="<p dir='ltr' style='text-align:left'>Start adding multiple key skills separated by comma.</p>"></i></a> --}}
                                    <input type="text" name="key_skills" id="key_skills" class="form-control tokenfield key_skills" value="" data-fouc>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Job Description <span class="required-star-color">*</span></label>
                                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Job Description">{{ $is_job ? $job->description : old('description') }}</textarea>
                                    @if($is_job)
                                        <input type="hidden" name="job_id" class="form-control" value="{{ ($is_job) ? $job->id : 0}}">
                                    @endif
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
    $('.key_skills').tokenfield({
        tokens:@json($inserted_key_skills),
        autocomplete: {
            source: @json($skills),
            delay: 100
        },
        limit : 10,
        // showAutocompleteOnFocus: true
        createTokensOnBlur: true,
    });

    $('.key_skills').on('tokenfield:createtoken', function (event) {
        var existingTokens = $(this).tokenfield('getTokens');
        //check the capitalized version
        // event.attrs.value =  capitalizeFirstLetter(event.attrs.value);
        $.each(existingTokens, function(index, token) {
            if ((token.label === event.attrs.value || token.value === event.attrs.value)) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/post_job.js') }}"></script>
@endsection
