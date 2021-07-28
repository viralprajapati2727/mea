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
                                                @if ($question->question_category_id > 0)
                                                    <a href="{{ route('page.questions').'?category_id='.$question->communityCategory->id }}">
                                                        {{ $question->communityCategory->title ? $question->communityCategory->title : '' }}
                                                    </a>
                                                @else
                                                    <p class="btn btn-primary">{{ ucfirst($question->other_category) ?? "-" }}</p>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                    @php
                                        $communityTags = array_filter($question->tags)
                                    @endphp
                                    @if(!empty($question->tags) && count($communityTags) > 0)
                                        <div class="quetion-tags">
                                            <ul>
                                                @foreach($communityTags as $tag)
                                                    <li>{{ $tag ?? "" }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endisset
                                    <div class="view-likes-wrap">
                                        {{ $question->countCommunityTotalLikes($question->id) }} 
                                        @guest
                                            Likes
                                        @else
                                        <div class="likes"> 
                                                @if ($question->checkIsLikedByCurrentUser($question->id) == true)
                                                    <a href="{{ route('community.questions-details',[
                                                        'question_id'=> $question->slug,
                                                        'like' => 10]) }}">
                                                        <span class="fa fas fa-thumbs-up"></span>
                                                        Unlike
                                                     </a>
                                                @else
                                                <a href="{{ route('community.questions-details',[
                                                    'question_id'=> $question->slug,
                                                    'like' => 1]) }}">
                                                    <span class="fa fas fa-thumbs-up"></span>
                                                        Likes
                                                     </a>
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