@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="page-wraper">
        <div class="quetions-lists-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="question-list">
                            @if(isset($questions) && sizeof($questions) > 0)
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
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quetions-sidebar">
                            <div class="categories">
                                <h2>categories</h2>
                                <ul class="categories-list">
                                    @foreach ($categories as $cat)
                                        <li><a href="{{ route('page.questions').'?category_id='.$cat->id }}">{{ $cat->title }}</a></li>
                                    @endforeach
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