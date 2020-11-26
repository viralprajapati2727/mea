
@extends('layouts.app')
@section('content')
<section>
    <div class="slider">
        <div class="container">
            <div id="home-slider" class="owl-carousel owl-theme">
                <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div>
                <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="solutions-section">
        <div class="container">
            <div class="solution-content">
                <div class="row">
                    <div class="offset-md-2 col-sm-8 text-center">
                        <h2> Helping founders build investable companies </h2>
                        <p class="lg"> Mission-critical business tools. Powerful performance benchmarks. Trusted fundraising recommendations. 
                        Muslim Entrepreneur supports you at every point along your entrepreneurial journey so when it’s time to raise money, you have the best shot at investment. Get started on the path to fundraising success: </p>
                    </div>
                </div>
            </div>
            <div class="solutions-inner">
                <div class="row">
                    <div class="col-md-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/solutions01.png') }}" alt="">
                            </div>
                            <h3 class="s-title"> Start a Company </h3>
                            <p class="desc"> Prepare for investment and growth with easy incorporation and legal tools. </p>
                            <a href="javascript:;" class="more-link">Learn More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/solutions02.png') }}" alt="">
                            </div>
                            <h3 class="s-title">  Grow Your Startup  </h3>
                            <p class="desc">  Make your company more attractive to investors with personalized recommendations.  </p>
                            <a href="javascript:;" class="more-link">Learn More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="solutions-box text-center">
                            <div class="s-image">
                               <img src="{{ Helper::assets('images/home/solutions03.png') }}" alt="">
                            </div>
                            <h3 class="s-title">  Raise Capital  </h3>
                            <p class="desc">  Discover your best sources for funding and find and apply for investment.  </p>
                            <a href="javascript:;" class="more-link">Learn More</a>
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
            <div class="home-features-inner">
                <div class="row">
                    <div class="col-md-6">
                        <div class="features-content">
                            <h2> The world’s largest startup network. </h2>
                            <p> With over 800,000 founders and 85,000 investment professionals on our platform, we’ll connect you to the whole startup ecosystem. 
                            We understand what investors want to see from a startup, and we can help you put your best foot forward. </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="features-image">
                        <img src="{{ Helper::assets('images/home/network.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-features-inner odd">
                <div class="row">
                    <div class="col-md-6">
                        <div class="features-image">
                        <img src="{{ Helper::assets('images/home/feedback.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="features-content">
                            <h2>Feedback and tools personalized to your journey.</h2>
                            <p>With powerful performance benchmarks, tailored feedback, and clear next steps for your startup, you’ll learn where to focus your efforts 
                            and find the tools you need to execute right at your fingertips. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-features-inner">
                <div class="row">
                    <div class="col-md-6">
                        <div class="features-content">
                            <h2>Designed to help you succeed.</h2>
                            <p>Our mission is to help founders win. Whether your next milestone is initially setting up your company the right way or successfully closing a 
                            Series C, we are here to help you hit it.  </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="features-image">
                            <img src="{{ Helper::assets('images/home/success.png') }}" alt="" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<Section>
    <div class="home-blog-section">
        <div class="container">
            <h2 class="blog-title text-center">Our Latest Blog</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="blog-wrap">
                        <div class="blog-image">
                            <img src="{{ Helper::assets('images/blog/Blog-1599029856.jpg') }}" alt="" class="w-100">
                        </div>
                        <div class="blog-header d-flex flex-wrap justify-content-between">
                            <div class="author">
                                <h6><span>-</span> by H. Rackham</h6>
                            </div>
                            <div class="blog-date">
                                <i class="icon-calendar"></i>
                                <span class="date_time">04 Aug 2020</span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <h2>The Key Drivers for Success</h2>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, 
                            or randomised words which don't look even slightly believable.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-wrap">
                        <div class="blog-image">
                            <img src="{{ Helper::assets('images/blog/blog02.png') }}" alt="" class="w-100">
                        </div>
                        <div class="blog-header d-flex flex-wrap justify-content-between">
                            <div class="author">
                                <h6><span>-</span> by H. Rackham</h6>
                            </div>
                            <div class="blog-date">
                                <i class="icon-calendar"></i>
                                <span class="date_time">04 Aug 2020</span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <h2>The Key Drivers for Success</h2>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, 
                            or randomised words which don't look even slightly believable.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-wrap">
                        <div class="blog-image">
                            <img src="{{ Helper::assets('images/blog/blog03.jpg') }}" alt="" class="w-100">
                        </div>
                        <div class="blog-header d-flex flex-wrap justify-content-between">
                            <div class="author">
                                <h6><span>-</span> by H. Rackham</h6>
                            </div>
                            <div class="blog-date">
                                <i class="icon-calendar"></i>
                                <span class="date_time">04 Aug 2020</span>
                            </div>
                        </div>
                        <div class="blog-content">
                                <h2>The Key Drivers for Success</h2>
                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, 
                                     or randomised words which don't look even slightly believable.</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer_script')
    <script type="text/javascript">

    </script>
@endsection