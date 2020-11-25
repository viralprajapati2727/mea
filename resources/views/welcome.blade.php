
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
@endsection
@section('footer_script')
    <script type="text/javascript">

    </script>
@endsection