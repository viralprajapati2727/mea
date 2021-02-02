@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="page-wraper">
        <div class="quetions-lists-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="question-list">
                            @isset($questions)
                            @foreach ($questions as $question)
                                <div class="question-box">
                                    <div class="q-top-detail d-flex align-items-center">
                                        <div class="profile-image">
                                            @php
                                                $ProfileUrl = Helper::images(config('constant.profile_url'));
                                                $img_url = (isset($question->user->logo) && $question->user->logo != '') ? $ProfileUrl . $question->user->logo : $ProfileUrl.'default.png';
                                            @endphp
                                            <img src="{{ $img_url }}" alt="">
                                        </div>
                                        <div class="name">
                                            <h3>{{ $question->user->name }}</h3>
                                            <span class="date">{{ $question->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="q-details">
                                        <div class="quetion-title">
                                            <h2>
                                                <a href={{ route("community.questions-details",$question->slug) }}>
                                                    {{  $question->title }}
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="quetion-desc">
                                            {!! $question->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @else
                                No Questions Found
                            @endisset
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quetions-sidebar">
                            <div class="categories">
                                <h2>categories</h2>
                                <ul class="categories-list">
                                    <li><a href="#">Accountant</a></li>
                                    <li><a href="#">Influencers</a></li>
                                    <li><a href="#">IT & Networking Services</a></li>
                                    <li><a href="#">Beta users & testers</a></li>
                                    <li><a href="#">Operations</a></li>
                                    <li><a href="#">Logistics</a></li>
                                    <li><a href="#">Sales</a></li>
                                    <li><a href="#">Business Development</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 