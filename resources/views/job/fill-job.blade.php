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
            <div class="d-md-flex justify-content-center">
                <div class="col-md-9 p-0">
                    <h2>Post a Job</h2>
                </div>
            </div>
            <div class="row mt-md-0 mt-3 pb-0 justify-content-center">
                <div class="col-lg-9 col-md-12">
                    <div class="mt-md-2 row unique-radio">
                        <div class="form-group col-md-12">
                            <div class="form-radio-group">
                                @if(!$is_job || $is_job && $job->job_type == 1)
                                <label class="radio-inline active">
                                    <i class="flaticon-job"></i>
                                    <input type="radio" class="job_type" name="job_type" value="1" checked="">Post a Job
                                </label>
                                @endif
                                @if(!$is_job || $is_job && $job->job_type == 2)
                                <label class="radio-inline {{ $is_job && $job->job_type == 2 ? 'active' : '' }}">
                                    <i class="flaticon-business-idea"></i>
                                    <input type="radio" class="job_type" name="job_type" value="2">Post a Request
                                </label>
                                @endif
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
            @if(!$is_job || $is_job && $job->job_type == 1)
                <div class="type_post_job">
                    <form class="post-job-form " action="{{ route('job.update-job') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <div class="row mt-md-0 mt-3 pb-0 justify-content-center">
                            <div class="col-lg-9 col-md-12">
                                <div class="row mt-md-2">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Job Title <span class="required-star-color">*</span></label>
                                            <select name="job_title_id" id="job_title_id" class="form-control select2 no-search-select2" data-placeholder="Select Job Title">
                                                <option></option>
                                                @forelse ($jobtitles as $jobtitle)
                                                    <option value="{{ $jobtitle->id }}" {{ ($is_job) ? ($job->job_title_id == $jobtitle->id ? 'selected' : ''): ''  }}>{{ $jobtitle->title }}</option>
                                                @empty
                                                @endforelse
                                                <option value="-1" {{ ($is_job) ? ($job->job_title_id == '-1' ? 'selected' : ''): ''  }}> Other </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Business Category <span class="required-star-color">*</span></label>
                                            <select name="business_category_id" id="business_category_id" class="form-control select2 no-search-select2" data-placeholder="Select Business Category">
                                                <option></option>
                                                @forelse ($business_categories as $category)
                                                    <option value="{{ $category->id }}" {{ ($is_job) ? ($job->business_category_id == $category->id ? 'selected' : ''): ''  }}>{{ $category->title }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                <div class="row div_other_job_title" style="display: {{ (!$is_job || $is_job && $job->job_title_id > 0) ? 'none' : 'block' }}">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="text-secondary">Other Job Title</label>
                                            <input type="text" name="other_job_title" id="other_job_title" class="form-control" placeholder="Other Job Title" value="{{ ($is_job && $job->job_type == 1) ? $job->other_job_title : old('other_job_title') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-md-12">
                                        <h5>Salary Range</h5>
                                    </div>
                                    <div class="col-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled is_paid" name="is_paid" value="1" data-fouc {{ ($is_job && $is_job && $job->is_paid > 0) ? 'checked' : '' }}>
                                                    Is Paid?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="salary_div w-100" style="display: {{ ($is_job && $job->is_paid > 0) ? 'block' : 'none' }}">
                                            <div class="row">
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Salary Type <span class="required-star-color">*</span></label>
                                                        <select name="salary_type_id" id="salary_type_id" class="form-control select2 no-search-select2" data-placeholder="Select Salary Type">
                                                            <option></option>
                                                            @forelse (config('constant.salary_type') as $key => $salarytype)
                                                                <option value="{{ $key }}" {{ ($is_job) ? ($job->salary_type_id == $key ? 'selected' : ''): ''  }}>{{ $salarytype }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Min <span class="required-star-color">*</span></label>
                                                        <input type="text" class="form-control min_salary" name="min_salary" id="min_salary" placeholder="Min Salary" value="{{ ($is_job) ? $job->min_salary : old('min_salary') }}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Max <span class="required-star-color">*</span></label>
                                                        <input type="text" class="form-control" name="max_salary" id="max_salary" placeholder="Max Salary" value="{{ ($is_job) ? $job->max_salary : old('max_salary') }}" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-md-12">
                                        <h5>Job Timing</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Start Time</label>
                                            <input type="text" class="form-control job_start_time" name="job_start_time" id="job_start_time" placeholder="Start Time" value="{{ ($is_job) ? $job->job_start_time : old('job_start_time') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-control-label">End Time</label>
                                            <input type="text" class="form-control" name="job_end_time" id="job_end_time" placeholder="End Time" value="{{ ($is_job) ? $job->job_end_time : old('job_end_time') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Time Zone</label>
                                            <input type="text" class="form-control" name="time_zone" id="time_zone" placeholder="Ex. Asia/Kuwait" value="{{ ($is_job) ? $job->time_zone : old('time_zone') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Job Location <span class="required-star-color">*</span></label>
                                            <input type="text" class="form-control location" name="location" id="location" placeholder="Job Location" value="{{ ($is_job && $job->job_type == 1) ? $job->location : old('location') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group key_skill">
                                            <label class="form-control-label">Key skills <span class="required-star-color">*</span></label>
                                            {{-- <a href="javascript:;"><i class="fa fa-info-circle ml-1" aria-hidden="true" data-popup="popover" title="" data-trigger="hover" data-html="true" data-content="<p dir='ltr' style='text-align:left'>Start adding multiple key skills separated by comma.</p>"></i></a> --}}
                                            <input type="text" name="key_skills" id="key_skills" class="form-control tokenfield key_skills" value="" data-fouc>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2 job_shift_wrap">
                                    <div class="col-12">
                                        <div class="radios">
                                            <label class="form-control-label">What shifts will they work? <span class="required-star-color">*</span></label>
                                            @foreach (config('constant.SHIFT') as $day => $shift)
                                                <div class="form-group">
                                                    <h6>{{ array_keys($shift)[0] }}</h6>
                                                    <div class="form-radio-group">
                                                        @foreach ($shift[array_keys($shift)[0]] as $key => $value)
                                                            @if($is_job)    
                                                                @php
                                                                    $shifts = $job->jobShift->toArray()
                                                                @endphp
                                                                @if($key == 1) {{-- day shift --}} 
                                                                        <label class="radio-inline {{ $shifts[$day-1]['day_shift_val'] == 1 ? "active" : "" }} ">
                                                                            <i class="{{ $value }}"></i>
                                                                            <input type="checkbox" class="job_type shift-radio" name="day_shift[{{ $day }}]" value="{{ 1 }}" {{ $shifts[$day-1]['day_shift_val'] == 1 ? "checked" : "" }} >
                                                                            </label>
                                                                @else {{-- night shift --}} 
                                                                        <label class="radio-inline {{ $shifts[$day-1]['night_shift_val'] == 1 ? "active" : "" }} ">
                                                                            <i class="{{ $value }}"></i>  
                                                                            <input type="checkbox" class="job_type shift-radio" name="night_shift[{{ $day }}]" value="{{ 1 }}" {{ $shifts[$day-1]['night_shift_val'] == 1 ? "checked" : "" }}>
                                                                            </label>
                                                                    @endif
                                                            @else
                                                                <label class="radio-inline">
                                                                    <i class="{{ $value }}"></i>
                                                                    @if($key == 1) {{-- day shift --}} 
                                                                        <input type="checkbox" class="job_type shift-radio" name="day_shift[{{ $day }}]" value="{{ 1 }}">
                                                                    @else {{-- night shift --}} 
                                                                        <input type="checkbox" class="job_type shift-radio" name="night_shift[{{ $day }}]" value="{{ 1 }}">
                                                                    @endif
                                                                </label>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group ckeditor">
                                            <label class="form-control-label">Job Description <span class="required-star-color">*</span></label>
                                            <textarea name="description" id="description" rows="5" class="form-control description" placeholder="Job Description">{{ $is_job && $job->job_type == 1 ? $job->description : old('description') }}</textarea>
                                            @if($is_job)
                                                <input type="hidden" name="job_id" class="form-control" value="{{ ($is_job) ? $job->id : 0}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                                    <input type="hidden" name="job_type" value="1">
                                    <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>SAVE</button>
                                    @if(isset($profile->is_profile_filled) && $profile->is_profile_filled == 1)
                                        <span class="pl-3 d-md-inline-block d-lg-inline-block pt-4 pt-md-0 pt-lg-0 text-center"><a href="{{route('index')}}" class="text-common-color font-semi-bold entry-cancel">CANCEL</a></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            @if(!$is_job || $is_job && $job->job_type == 2)
                <div class="type_post_request" style="display: {{ $is_job && $job->job_type == 2 ? 'block' : 'none' }}">
                    <form class="post-request-form" action="{{ route('job.update-job') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <div class="row mt-md-0 mt-3 pb-0">
                            <div class="col-lg-9 col-md-12">
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Post Title <span class="required-star-color">*</span></label>
                                            <input type="text" class="form-control" name="other_job_title" placeholder="Post Title" value="{{ ($is_job && $job->job_type == 2) ? $job->other_job_title : old('other_job_title') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Location</label>
                                            <input type="text" class="form-control " name="location" placeholder="Location" id="location_new" value="{{ ($is_job && $job->job_type == 2) ? $job->location : old('location') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled is_find_team_member" name="is_find_team_member" value="1" data-fouc {{ ($is_job && $is_job && $job->is_find_team_member > 0) ? 'checked' : '' }}>
                                                    Find a Team Member?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2 team_member_text_div" style="display: {{ ($is_job && $job->is_find_team_member > 0) ? 'block' : 'none' }}">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">What type of team member are you looking for?</label>
                                            <input type="text" class="form-control" name="find_team_member_text" placeholder="Find a Team Member" value="{{ ($is_job) ? $job->find_team_member_text : old('find_team_member_text') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-12">
                                        <div class="form-group ckeditor">
                                            <label class="form-control-label">Post Description <span class="required-star-color">*</span></label>
                                            <textarea name="r_description" id="r_description" rows="5" class="form-control r_description" placeholder="Post Description">{{ $is_job && $job->job_type == 2 ? $job->description : old('description') }}</textarea>
                                            @if($is_job)
                                                <input type="hidden" name="job_id" class="form-control" value="{{ ($is_job) ? $job->id : 0}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                                    <input type="hidden" name="job_type" value="2">
                                    <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>SAVE</button>
                                    @if(isset($profile->is_profile_filled) && $profile->is_profile_filled == 1)
                                        <span class="pl-3 d-md-inline-block d-lg-inline-block pt-4 pt-md-0 pt-lg-0 text-center"><a href="{{route('index')}}" class="text-common-color font-semi-bold entry-cancel">CANCEL</a></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('footer_script')
<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key={{ env('PLACE_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}"></script>
<script>
$(document).ready(function () {
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

    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
        google.maps.event.addDomListener(input, 'keydown', function(event) { 
            if (event.keyCode === 13) { 
                event.preventDefault(); 
            }
        }); 
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
        
            // $('#latitude').val(place.geometry['location'].lat());
            // $('#longitude').val(place.geometry['location'].lng());
            // $("#latitudeArea").removeClass("d-none");
            // $("#longtitudeArea").removeClass("d-none");
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize_new);

    function initialize_new() {
        var input = document.getElementById('location_new');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
        google.maps.event.addDomListener(input, 'keydown', function(event) { 
            if (event.keyCode === 13) { 
                event.preventDefault(); 
            }
        }); 
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
        });
    }
});

</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/post_job.js') }}"></script>
@endsection
