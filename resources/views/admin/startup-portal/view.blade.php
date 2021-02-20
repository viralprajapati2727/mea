@extends('admin.app-admin')
@section('title') Startup Portal Details @endsection
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
<h6 class="card-title text-center">Startup Details</h6>
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
                            <p class="font-normal">{{ $startup->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Description</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal"></p>{!! $startup->description !!}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Industry</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->industry }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Location</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->location }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Stage of Startup</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ config("constant.stage_of_startup")[$startup->stage_of_startup] }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Team Members</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">
                                @forelse ($startup->startup_team_member as $member)
                                @if ($member->status == 1)
                                    {{ $member->user->name ?? "" }} ({{ $member->user->email ?? "-" }}) <br>
                                @endif
                                @empty
                                -
                                @endforelse
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">What’s the most important next step for your startup?</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->important_next_step > 0 ? config('constant.most_important_next_step_for_startup')[$startup->important_next_step] : $startup->other_important_next_step }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Stage of Startup</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{  config("constant.stage_of_startup")[$startup->stage_of_startup] }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Website Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->web_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Facebook Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->fb_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Instagram Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->insta_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Twitter Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->tw_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Linked Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->linkedin_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Tiktok Link</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $startup->tiktok_link ?? "-" }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <span class="job-status status-{{ strtolower($statuss[$startup->status]) }} mr-2">{{ $statuss[$startup->status] }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Business Plan</label>
                        </div>
                        <div class="col-lg-8">
                            @if(isset($startup->business_plan) && $startup->business_plan != "")
                                <a href="{{ $exists_businessplan }}" target="_blank">Download Your Business Plan</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Financial</label>
                        </div>
                        <div class="col-lg-8">
                            @if(isset($startup->financial) && $startup->financial != "")
                                <a href="{{ $exists_financial }}" target="_blank">Download Your Financial</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Pitch Deck</label>
                        </div>
                        <div class="col-lg-8">
                            @if(isset($startup->pitch_deck) && $startup->pitch_deck != "")
                                <a href="{{ $exists_pitch_deck }}" target="_blank">Download Your Pitch Deck</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Allow users to view:</label>
                        </div>
                        <div class="col-lg-8">
                            <p>{{ $startup->is_view > 0 ? "Allowed" : "Not allowed" }}</p>
                        </div>
                    </div>
                </div>
            </div>
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