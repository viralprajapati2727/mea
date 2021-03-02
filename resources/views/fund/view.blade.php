@extends('layouts.app')
@section('content')
@php
    $statuss = config('constant.appointment_status');
    $share_url = Request::url();
    
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
<div class="page-main">
    <div class="page-wraper">
        <div class="job-top-details">
            <div class="container">
                <div class="job-inner-wrap">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="job-details-left">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="job-details-inner">
                                            <h2 class="job-title">{{ $startup->name ?? ""}}</h2>
                                            <div class="d-sm-inline d-inline-block mr-3">
                                                <i class="fa fa-map-marker"></i>
                                                <span>{{ $startup->location ?? "" }}</span>
                                            </div>
                                            <div class="mr-3 mt-2">
                                                <span class="job-status status-{{ strtolower($statuss[$startup->status]) }} mr-2">{{ $statuss[$startup->status] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            @isset($startup->appoinment->time)
                                <div class="job-detail-list">
                                    <label class="lable">Appointment Date & Time :</label>
                                    <p>{{ $startup->appoinment->date ."  ". $startup->appoinment->time ?? "" }}</p>
                                </div>
                                <div class="job-detail-list">
                                    <label class="lable">Appointment Zone:</label>
                                    <p>{{ $startup->appoinment->zone ?? "" }}</p>
                                </div>
                                <div class="job-detail-list">
                                    <label class="lable">Purpose of Meeting:</label>
                                    <p>{{ $startup->appoinment->purpose_of_meeting ?? "" }}</p>
                                </div>
                                <div class="job-detail-list">
                                    <label class="lable">Appointment Status:</label>
                                    <span class="job-status status-{{ strtolower($statuss[$startup->appoinment->status]) }} mr-2">{{ $statuss[$startup->appoinment->status] }}</span>
                                </div>
                                <div class="job-detail-list">
                                    <label class="lable"> </label>
                                    <p> </p>
                                </div>
                                @if($startup->appoinment->status == 2)
                                    <div class="job-detail-list">
                                        <label class="lable">Reject reason:</label>
                                        <p>{{ $startup->appoinment->reason }}</p>
                                    </div>
                                @endif
                                @else
                                <div class="add-que-wrap d-flex justify-content-end">
                                    @if ($startup->status == 1)
                                        <button type="button" class="btn" data-toggle="modal" data-target="#schedule-appoinment">Schedule an appointment</button>
                                    @endif
                                </div>    
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="job-wrap job-details">
                    <div class="job-detail-list">
                        <label class="lable">Startup Description:</label>
                        <p>{!! $startup->description ?? "" !!}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">industry:</label>
                        <p>{{ $startup->industry ?? "" }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Team Members:</label>
                        <p>
                            @forelse ($startup->startup_team_member as $member)
                                @if ($member->status == 1)
                                    {{ $member->user->name ?? "" }} ({{ $member->user->email ?? "-" }}) <br>
                                @endif
                            @empty
                            @endforelse
                        </p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Stage of Startup:</label>
                        <p>{{ config("constant.stage_of_startup")[$startup->stage_of_startup] }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">What’s the most important next step for your startup?:</label>
                        <p>{{ $startup->important_next_step > 0 ? config('constant.most_important_next_step_for_startup')[$startup->important_next_step] : $startup->other_important_next_step }}</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Website Link:</label>
                        <p><a href="{{ $startup->web_link ?? 'javascript:;' }}" target="_blank">{{ $startup->web_link ?? "-" }}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Facebook Link:</label>
                        <p><a href="{{ $startup->fb_link ?? 'javascript:;' }}" target="_blank">{{ $startup->fb_link ?? "-"}}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Instagram Link:</label>
                        <p><a href="{{ $startup->insta_link ?? 'javascript:;' }}" target="_blank">{{ $startup->insta_link ?? "-"}}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Twitter Link:</label>
                        <p><a href="{{ $startup->tw_link ?? 'javascript:;' }}" target="_blank">{{ $startup->tw_link ?? "-"}}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Linkedin Link:</label>
                        <p><a href="{{ $startup->linkedin_link ?? 'javascript:;' }}" target="_blank">{{ $startup->linkedin_link ?? "-" }}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Tiktok Link:</label>
                        <p><a href="{{ $startup->tiktok_link ?? 'javascript:;' }}" target="_blank">{{ $startup->tiktok_link ?? "-"}}</a></p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Business Plan:</label>
                        @if(isset($startup->business_plan) && $startup->business_plan != "")
                            <a href="{{ $exists_businessplan }}" target="_blank">Download Your Business Plan</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Financial:</label>
                        @if(isset($startup->financial) && $startup->financial != "")
                            <a href="{{ $exists_financial }}" target="_blank">Download Your Financial</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Pitch Deck:</label>
                        @if(isset($startup->pitch_deck) && $startup->pitch_deck != "")
                            <a href="{{ $exists_pitch_deck }}" target="_blank">Download Your Pitch Deck</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Allow users to view:</label>
                        <p>{{ $startup->is_view > 0 ? "Allowed" : "Not allowed" }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- schedule-appoinment -->
<div id="schedule-appoinment" class="modal fade" tabindex="-1"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule an appointment</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form class="ask_question_form" action="{{ route('store-appoinment') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="startup_id" value="{{ $startup->id }}">
                <div class="modal-body">
                    <div class="row mt-md-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Date <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="date" id="date" placeholder="Enter Date" value="{{ old('date') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Time <span class="required-star-color">*</span></label>
                                <input type="text" name="time" id="time" class="form-control" placeholder="Enter Time" required value="{{ old("time") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group tag">
                        <label class="form-control-label">Zone <span class="required-star-color">*</span></label>
                        <input type="text" name="zone" id="zone" class="form-control"  placeholder="Ex. Asia/Kuwait" value="{{ old("zone") }}" required>
                    </div>
                    <div class="form-group mt-md-2 ckeditor">
                        <label class="col-form-label">Purpose of meeting <span class="required-star-color">*</span></label>
                        <div class="input-group custom-start">
                            <textarea name="purpose_of_meeting" id="purpose_of_meeting" rows="5" placeholder="Purpose of meeting:" class="form-control" required></textarea>
                        </div>
                    </div>
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
    <script>
        let today = new Date();
        $("#time").datetimepicker({
            ignoreReadonly: true,
            format: 'LT',
            useCurrent: false,
            locale: 'en'
        });
        $("#date").datetimepicker({
            ignoreReadonly: true,
            format: 'L',
            useCurrent: false,
            locale: 'en',
            minDate: today
        });
    </script>
@endsection