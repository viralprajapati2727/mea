
@extends('layouts.app')
@section('content')
<section>
    <div class="slider">
        <div>
            <div id="home-slider" class="owl-carousel owl-theme">
                <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div>
                {{-- <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div> --}}
            </div>
        </div>
    </div>
</section>
<section>
    <div class="solutions-section">
        <div class="container">
            <div class="solution-content">
                <div class="row">
                    <div class="offset-md-2 col-md-8 text-center">
                        <h2> Helping professionals and entrepreneurs connect, grow and achieve their dreams! </h2>
                        <p class="lg"> Our virtual incubator helps you connect with Muslim Professionals and Entrepreneurs from all over the world to exchange ideas, collaborate and grow! You have the ability to post questions and gain different perspectives, find members with a particular skill set or in a specific location, set up appointments with members, post and apply for both jobs and business requests and raise capital for your venture all on the platform! </p>
                    </div>
                </div>
            </div>
            <div class="solutions-inner">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/rocket.png') }}" alt="">
                            </div>
                            <h3 class="s-title"> Start a Company or New Career </h3>
                            <p class="desc"> Prepare for personal development with our advanced tools. </p>
                            <a href="{{ route('login') }}" class="more-link">Learn More</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/statistics.png') }}" alt="">
                            </div>
                            <h3 class="s-title">  Grow Your Startup or Personal Brand  </h3>
                            <p class="desc">  Scale your company and personal brand with help from our global network  </p>
                            <a href="{{ route('login') }}" class="more-link">Learn More</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/capital.png') }}" alt="">
                            </div>
                            <h3 class="s-title">  Raise Capital  </h3>
                            <p class="desc">  Secure various funding opportunities for your business or startup  </p>
                            <a href="{{ route('login') }}" class="more-link">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="home-features-section">
        <div class="container">
            <div class="home-features-inner even">
                <div class="row">
                    <div class="col-md-6 features-content-wraper">
                        <div class="features-content">
                            <h2> The world’s largest muslim startup and professional network </h2>
                            <p> With thousands of members worldwide, MEA is your personal network to help you grow your career, business or startup. We strive to empower our members with all the tools and knowledge to succeed in their careers and see their passions become a reality! </p>
                        </div>
                    </div>
                    <div class="col-md-6 features-image-wraper">
                        <div class="features-image">
                        <img src="{{ Helper::assets('images/home/jse.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-features-inner odd">
                <div class="row">
                    <div class="col-md-6">
                        <div class="features-image">
                        <img src="{{ Helper::assets('images/home/feedback1.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="features-content">
                            <h2>Feedback and tools personalized for your growth.</h2>
                            <p>With powerful collaboration tools, custom feedback and guidance on the next steps for your startup or career path, you’ll learn where to focus your efforts and find the strategies you need to execute right at your fingertips. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-features-inner even">
                <div class="row">
                    <div class="col-md-6 features-content-wraper">
                        <div class="features-content">
                            <h2>Designed to help you succeed.</h2>
                            <p>Our mission is to help our members succeed. Whether your next milestone is initially setting up your company the right way, raising capital or planning your next career move, we are here to help make it happen.  </p>
                        </div>
                    </div>
                    <div class="col-md-6 features-image-wraper">
                        <div class="features-image">
                            <img src="{{ Helper::assets('images/home/success01.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<Section>
    @if(!empty($blogs) && $blogs->count())
        <div class="home-blog-section">
            <div class="container">
                <h2 class="blog-title text-center">Our Latest Blog</h2>
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
                                    <h6><span>-</span> by @if ($blog->author_by) {{ $blog->author_by }} @else {{ $blog->user->name }} @endif
                                    </h6>
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
                <div class="text-center">
                    <a href="{{ route('page.blogs') }}" class="view-more-link">View more blogs</a>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection
@section('footer_script')
    <script type="text/javascript">

    </script>
@endsection