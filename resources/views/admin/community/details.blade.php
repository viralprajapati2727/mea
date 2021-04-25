@extends('admin.app-admin')
@section('title') Question Details @endsection
@section('page-header')
@php
$statuss = config('constant.job_status');
@endphp
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <span class="breadcrumb-item active">Question Details</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<h6 class="card-title text-center">Question Details</h6>
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
                                        $img_url = (isset($question->user->logo) && $question->user->logo != '') ?
                                        $ProfileUrl . $question->user->logo : $ProfileUrl.'default.png';
                                        @endphp
                                        <img src="{{ $img_url }}" alt=""
                                            style="height:50px; width:50px;background-size:cover;border-radius:50%">
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
                                        @if ($question->question_category_id > 0)
                                            <a href="{{ route('page.questions').'?category_id='.$question->communityCategory->id }}">
                                                {{ $question->communityCategory->title ? $question->communityCategory->title : '' }}
                                            </a>
                                        @else
                                            <p class="btn btn-primary">{{ ucfirst($question->other_category) ?? "-" }}</p>
                                        @endif
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
                                            Likes
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
                    <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection