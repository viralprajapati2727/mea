@extends('layouts.app')
@section('content')
@php
    $businessUrl = Helper::images(config('constant.business_plan'));
    $financialUrl = Helper::images(config('constant.financial'));
    $pitchdeckUrl = Helper::images(config('constant.pitch_deck'));

    $exists_businessplan = "";
    if(isset($startup)){
        if($startup->business_plan != ""){
            $is_same_business_plan = true;
            $exists_businessplan = $businessUrl.$startup->business_plan;
        }
        $exists_financial = "";
        if($startup->financial != ""){
            $is_same_financial = true;
            $exists_financial = $financialUrl.$startup->financial;
        }
        $exists_pitch_deck = "";
        if($startup->pitch_deck != ""){
            $is_same_pitch_deck = true;
            $exists_pitch_deck = $pitchdeckUrl.$startup->pitch_deck;
        }
    }

@endphp
<div class="container">
    <div class="user_profile_form_page fill-profile">
        <div class="d-md-flex justify-content-center">
            <div class="col-md-9 p-0">
                <h2>StartUp Portal</h2>
            </div>
        </div>
        <div class="startup_portal">
            <form class="startup-portal-form " action="{{ route('startup-portal.store') }}" data-fouc method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @method('POST')
                @csrf
                <input type="hidden" name="id" value="{{ $startup['id'] ?? "" }}">
                <input type="hidden" name="status" value="{{ $startup['status'] ?? "0" }}">
                <div class="row mt-md-0 mt-3 pb-0 justify-content-center">
                    <div class="col-lg-9 col-md-12">
                        <div class="row mt-md-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Name <span
                                            class="required-star-color">*</span></label>
                                    <input type="text" class="form-control " name="name" id="name" placeholder="Name"
                                        value="{{ $startup['name'] ?? old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group ckeditor">
                                    <label class="form-control-label">Description <span
                                            class="required-star-color">*</span></label>
                                    <textarea name="description" id="description" rows="5"
                                        class="form-control description"
                                        placeholder="Description">{{  $startup['description'] ?? old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Industry <span
                                            class="required-star-color">*</span></label>
                                    <input type="text" class="form-control " name="industry" id="industry" placeholder="Industry"
                                        value="{{ $startup['industry'] ?? old('industry') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Location <span
                                            class="required-star-color">*</span></label>
                                    <input type="text" class="form-control " name="location" id="location" placeholder="Location"
                                        value="{{ $startup['location'] ?? old('location') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Team Member </label>
                                    <select name="team_members[]" id="team_members" class="form-control select2 no-search-select2" data-placeholder="Select Team Member" multiple>
                                        <option></option>
                                        @forelse ($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{-- {{ ($startup) ? ($startup->business_category_id == $category->id ? 'selected' : ''): ''  }} --}}
                                                {{ $startup ? (in_array($user->id, $startup->startup_team_member->pluck("user_id")->toArray()) ? "selected" : "" ) : ""  }}
                                                >{{ __($user->name.' '.( $user->email)) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Stage of Startup <span class="required-star-color">*</span></label>
                                    <select name="startup_stage" id="startup_stage" class="form-control select2 no-search-select2" data-placeholder="Select Stage of Startup">
                                        <option></option>
                                        @forelse (config('constant.stage_of_startup') as $key => $stage)
                                            <option value="{{ $key }}" 
                                                {{ ($startup) ? ($startup->stage_of_startup == $key ? 'selected' : ''): ''  }}
                                                >{{ __($stage) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">What’s the most important next step for your startup? <span class="required-star-color">*</span></label>
                                    <select name="important_next_step" id="important_next_step" class="form-control select2 no-search-select2" data-placeholder="Select What’s the most important next step for your startup?">
                                        <option></option>
                                        @forelse (config('constant.most_important_next_step_for_startup') as $key => $step)
                                            <option value="{{ $key }}" 
                                                {{ ($startup) ? ($startup->important_next_step == $key ? 'selected' : ''): ''  }}
                                                >{{ __($step) }}</option>
                                        @empty
                                        @endforelse
                                        <option value="-1"> Other </option>
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="row div_other_important_next_step" style="display: {{ (!$startup || $startup && $startup->important_next_step > 0) ? 'none' : 'block' }}" >
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-secondary">Other Most Important Next Step</label>
                                    <input type="text" name="other_important_next_step" id="div_other_important_next_step" class="form-control" placeholder="Other Most Important Next Step" value="{{ ($startup && $startup->important_next_step == 1) ? $startup->important_next_step : old('div_other_important_next_step') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Website</label>
                                    <input type="text" class="form-control" name="web_link" id="web_link" placeholder="Enter Website Link" value="{{ $startup['web_link'] ?? old('web_link') }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Facebook Link</label>
                                    <input type="text" class="form-control" name="fb_link" id="fb_link" placeholder="Enter Facebook Link" value="{{ $startup['fb_link'] ?? old('fb_link') }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Instagram Link</label>
                                    <input type="text" class="form-control" name="insta_link" id="insta_link" placeholder="Enter Instagram Link" value="{{ $startup['insta_link'] ?? old('insta_link' ) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Twitter Link</label>
                                    <input type="text" class="form-control" name="tw_link" id="tw_link" placeholder="Enter Twitter Link" value="{{ $startup['tw_link'] ?? old('tw_link') }}" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">LinkedIn Link</label>
                                    <input type="text" class="form-control" name="linkedin_link" id="linkedin_link" placeholder="Enter LinkedIn Link" value="{{ $startup['linkedin_link'] ?? old('linkedin_link') }}" >
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">TikTok Link</label>
                                    <input type="text" class="form-control" name="tiktok_link" id="tiktok_link" placeholder="Enter TikTok Link" value="{{ $startup['tiktok_link'] ?? old('tiktok_link') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-md-2 fileinput">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Upload Business Plan:</label>
                            <div class="col-lg-9">
                                <input type="file" name="fileinput_business_plan" class="fileinput-business-plan" data-fouc>
                            </div>
                            @if(isset($startup->business_plan) && $startup->business_plan != "")
                                <div class="col-lg-10">
                                    <a href="{{ $exists_businessplan }}" target="_blank">Download Your Business Plan</a>
                                </div>
                                <input type="hidden" name="old_business_plan" value="{{ $startup->business_plan }}">
                            @endif
                        </div>
                        <div class="form-group row mt-md-2 fileinput">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Upload Financial:</label>
                            <div class="col-lg-9">
                                <input type="file" name="fileinput_financial" class="fileinput-financial" data-fouc>
                            </div>
                            @if(isset($startup->financial) && $startup->financial != "")
                                <div class="col-lg-10">
                                    <a href="{{ $exists_financial }}" target="_blank">Download Your Financial</a>
                                </div>
                                <input type="hidden" name="old_financial" value="{{ $startup->financial }}">
                            @endif
                        </div>
                        <div class="form-group row mt-md-2 fileinput">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Upload Pitch Deck:</label>
                            <div class="col-lg-9">
                                <input type="file" name="fileinput_pitch_deck" class="fileinput-pitch-deck" data-fouc>
                            </div>
                            @if(isset($startup->pitch_deck) && $startup->pitch_deck != "")
                                <div class="col-lg-10">
                                    <a href="{{ $exists_pitch_deck }}" target="_blank">Download Your Pitch Deck</a>
                                </div>
                                <input type="hidden" name="old_pitch_deck" value="{{ $startup->pitch_deck }}">
                            @endif
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-md-12">
                                <div class="form-check-inline">
                                    <label>
                                        <input type="checkbox" name="is_view" id="is_view" class="form-check-input-styled" value="1" {{ (isset($startup) && $startup->is_view == 1) ? 'checked' : '' }}> Allow users to view your documents?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                            <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>SAVE</button>
                            <span class="pl-3 d-md-inline-block d-lg-inline-block pt-4 pt-md-0 pt-lg-0 text-center"><a href="{{route('startup-portal')}}" class="text-common-color font-semi-bold entry-cancel">CANCEL</a></span>
                            
                        </div>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}"></script>
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
    var is_form_edit = false;
    $('.key_skills').tokenfield({
        tokens:@json($inserted_key_skills ?? ''),
        autocomplete: {
            source: @json($skills ?? ''),
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
<script type="text/javascript" src="{{ Helper::assets('js/pages/startup_portal.js') }}"></script>
<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key={{ env('PLACE_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
<script>
$(document).ready(function () {
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
        });
    }
});

</script>
@endsection