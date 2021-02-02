@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="page-wraper">
        <div class="quetions-lists-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="question-list">
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
                                            {{  $question->title }}
                                        </h2>
                                    </div>
                                    <div class="quetion-desc">
                                        {!! $question->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @comments([
                            'model' => $question, 
                            'approved' => true, // if true comment will auto approved
                            'maxIndentationLevel'=> 3, // maximum replay to comment
                            'perPage' => 5 // pagination
                        ])
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 