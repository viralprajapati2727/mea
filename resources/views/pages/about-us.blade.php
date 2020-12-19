@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner about-us">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1>About us</h1>
                    <p>Our business is matching smart prople with great jobs</p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
        <div class="who-we-are">
            <div class="container">
                <div class="heading text-center">
                    <h2>Who We Are</h2>
                </div>
                <div class="content">
                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or 
                    randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything 
                    embarrassing hidden in the middle of text.</p>
                </div>
            </div>
        </div>
        <div class="our-mission about-column">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="image-wrap">
                            <img src="{{ Helper::assets('images/About/mission.jpg') }}" alt="">
                        </div>
                    </div>
                    <div class="col-md-7 content-wraper">
                        <div class="content-inner">
                            <h2 class="title">Our Mission</h2>
                            <div class="text">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard 
                                dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="our-vision about-column">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 content-wraper">
                        <div class="content-inner">
                            <h2 class="title">Our Vision</h2>
                            <div class="text">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard 
                                dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="image-wrap">
                            <img src="{{ Helper::assets('images/About/vision.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="our-mission about-column">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="image-wrap"> 
                            <img src="{{ Helper::assets('images/About/values.jpg') }}" alt="" class="w-100">
                        </div>
                    </div>
                    <div class="col-md-7 content-wraper">
                        <div class="content-inner">
                            <h2 class="title">Our Values</h2>
                            <div class="text">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard 
                                dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="who-we-are why-us">
            <div class="container">
                <div class="heading text-center">
                    <h2>Why Us?</h2>
                </div>
                <div class="content">
                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or 
                    randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything 
                    embarrassing hidden in the middle of text.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection