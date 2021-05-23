@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner our-blog">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1>Our Blog</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
      <div class="blog-page-wraper">
        <div class="home-blog-section">
            <div class="container">
                @if(!$blogs->isEmpty())
                    <div class="row">
                        @forelse ($blogs as $blog)
                            <div class="col-md-4">
                                <div class="blog-wrap">
                                    <div class="blog-image">
                                        @php
                                            $blogurl = Helper::images(config('constant.blog_url'));
                                            $img_url = $blogurl.$blog->src;
                                        @endphp
                                        <a href="{{ route('page.blog-detail',['slug' => $blog->slug]) }}"><img src="{{ $img_url }}" alt="" class="w-100"></a>
                                    </div>
                                    <div class="blog-header d-flex flex-wrap justify-content-between">
                                        <div class="author">
                                            <h6><span>-</span> by  @if ($blog->author_by) {{ $blog->author_by }} @else {{ $blog->user->name }} @endif</h6>
                                        </div>
                                        <div class="blog-date">
                                            {{-- <i class="icon-calendar"></i>
                                            <span class="date_time">{{ Carbon\Carbon::parse($blog->updated_at)->format('j M Y') }}</span> --}}
                                            @if ($blog->published_at)
                                                <i class="icon-calendar"></i>
                                                <span class="date_time">{{ Carbon\Carbon::parse($blog->published_at)->format('j M Y') }}</span>
                                            @else
                                                <i class="icon-calendar"></i>
                                                <span class="date_time">{{ Carbon\Carbon::parse($blog->updated_at)->format('j M Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="blog-content">
                                        <a href="{{ route('page.blog-detail',['slug' => $blog->slug]) }}"><h2>{{ $blog->title }}</h2></a>
                                        <p>{{ $blog->short_description }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="pagination my-5">
                        {{ $blogs->onEachSide(1)->links() }}
                    </div>
                @else
                    <div class="pagination my-5">No Blogs Found!!</div>
                @endif
            </div>
        </div>
      </div>
    </div>
</div>
@endsection