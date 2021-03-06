@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="page-wraper">
        <div class="quetions-lists-wraper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
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
                                <hr>
                                <div class="quetion-info">
                                    <div class="quetion-category">
                                        <ul>
                                            <li>
                                                <a href="{{ route('page.questions').'?category_id='.$question->communityCategory->id }}">
                                                    {{ $question->communityCategory->title ? $question->communityCategory->title : '' }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    @isset($question->tags)
                                        <div class="quetion-tags">
                                            <ul>
                                                @foreach($question->tags as $tag)
                                                    <li>{{ $tag ?? "" }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endisset
                                    <div class="view-likes-wrap">
                                        <div class="likes">
                                            <div class="fa fas fa-thumbs-up"></div> 
                                            {{ $question->countCommunityTotalLikes($question->id) }} 
                                            @guest
                                                Likes
                                            @else
                                                @if ($question->checkIsLikedByCurrentUser($question->id) == true)
                                                    <a href="{{ route('community.questions-details',[
                                                        'question_id'=> $question->slug,
                                                        'like' => 10]) }}">Unlike </a>
                                                @else
                                                    <a href="{{ route('community.questions-details',[
                                                        'question_id'=> $question->slug,
                                                        'like' => 1]) }}">Likes </a>
                                                @endif
                                            @endguest
                                        </div>
                                        <div class="views">
                                            <div class="fa fas fa-eye"></div> {{ $question->views }} <span>Views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @comments([
                            'model' => $question, 
                            'approved' => true, // if true comment will auto approved
                            'maxIndentationLevel'=> 4, // comment replay indentation level 
                            'perPage' => 10 // pagination
                        ])
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 