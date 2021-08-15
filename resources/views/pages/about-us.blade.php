@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner about-us">
        <div class="content-wraper">
            <div class="container">
                <div class="content about-content">
                    <h1>About us</h1>
                    <p>Our goal is to build a global community of professionals and entrepreneurs!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
        <div class="who-we-are">
            <div class="container">
                <div class="heading text-center">
                    <h2>Who Are We?</h2>
                </div>
                <div class="content">
                    <p>We are comprised of diverse professionals, entrepreneurs, students and community leaders looking to make a positive impact in the world. From striving to be at the top of our professions in healthcare, technology, financial, real estate and various industries to; creating and running successful startups, we want to both empower and make a positive impact in our global communities.</p>
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
                                <p>We at MEA are on a mission to build a global team of entrepreneurs and professionals. This platform provides the space and tools to assist with collaborating on different ventures, gain insightful feedback, post jobs and find team members while also helping those just starting out with their business or startup with the resources they need, and to raise capital to help their dream become a reality! </p>
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
                                <p>We strive to see the next latest companies, apps, startups and ventures be built, run and funded by members of our community. It is time for us collectively to come together and help others achieve their goals so we can add value and make an impact! </p>
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
                                <p>As we all strive to be our best and make our dreams a reality we come to realize that this road is extremely tough and riddles with obstacles. We must always believe in ourselves, our team and our vision even when all seems hopeless. </p>
                                <p>Every successful person, entrepreneur, business, startup, etc can equate their success down to these main fundamentals which we want all of our members to master: </p>
                                <ol>
                                    <li>Control</li>
                                    <li>Discipline</li>
                                    <li>Hard Work</li>
                                    <li>Consistency</li>
                                    <li>Resiliency</li>
                                </ol>
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
                    <p>We at MEA come from all walks of life and professional backgrounds. We all had to endure different challenges and overcome difficult obstacles, which is why we would like to help others on their path towards success. As passion led us here it will be our collective effort and core values that will help all of us reach our goals and make a positive impact!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection