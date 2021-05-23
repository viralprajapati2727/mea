@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="single-blog-wraper">
        <div class="container">
            <div class="single-blog-image">
                @php
                    $blogurl = Helper::images(config('constant.blog_url'));
                    $img_url = $blogurl.$blog->src;
                @endphp
                <a href="{{ route('page.blog-detail',['slug' => $blog->slug]) }}"><img src="{{ $img_url }}" alt="" class="w-100"></a>
            </div>
            <div class="single-blog-content">
                <div class="blog-top-detail d-sm-flex justify-content-between">
                    @if ($blog->published_at)
                        <div class="datetime">
                            <i class="icon-calendar"></i>
                            <span class="date_time">{{ Carbon\Carbon::parse($blog->published_at)->format('j M Y') }}</span>
                        </div>
                    @else
                        <div class="datetime">
                            <i class="icon-calendar"></i>
                            <span class="date_time">{{ Carbon\Carbon::parse($blog->updated_at)->format('j M Y') }}</span>
                        </div>
                    @endif
                    <div class="d-flex align-items-center share-blog-button">
                        <h5>Share:</h5>
                        <div class="d-inline-block">
                            <ul class="list social-share blog_share_icon d-flex">
                                <li class="share-fb"><a href="https://www.facebook.com/sharer/sharer.php?u{{url()->current()}}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li class="share-twit"><a href="https://twitter.com/intent/tweet?text=Mea&amp;url={{url()->current()}}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li class="share-linked"><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url()->current()}}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="blog-content">
                    <div class="blog-title">
                        <a href="{{ route('page.blog-detail',['slug' => $blog->slug]) }}"><h1>{{ $blog->title }}</h1></a>
                    </div>
                    <div class="blog-description">
                        {!! $blog->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection