@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="business-ideas-wraper">
        <div class="top-title-wrap">
            <div class="container">
                <h1>Startup Portal</h1>
            </div>
        </div>
        <div class="our-process">
            <div class="container">
                <div class="title">
                    <h2>Our Process</h2>
                </div>
                <div class="proceess-step">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-deal"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Introduce your company & tell us your story</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-question"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Answer a few questions</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-accounting"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We work on a financial projection model</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-cloud"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We set up a cloud based syatem (if needed)</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-deal"></i>
                                </div>
                                <div class="process-title">
                                    <h3>Receive financial statements</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <div class="process-inner">
                                <div class="process-icon">
                                    <i class="flaticon-checklist"></i>
                                </div>
                                <div class="process-title">
                                    <h3>We review your Business Plan</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="process-bottom">
                    <p>A business begins with the plan and financial tools for success we help you shapw and define both. 
                    Our process appropriately situates an entrepreneur to <strong>define and namage the business pan</strong> and <strong>avoid the pitfalls</strong> of getting buried in the details.</p>
                </div>
            </div>
        </div>
        <div class="building-plan">
            <div class="container">
                <div class="title"><h2>Building Your Plan</h2></div>
                <p>The fear concern, time and resources needed for setting up astartup seems daunting at first glance - but it doesn't have to be! 
                    Startup Portal evolved from years of experience in capital raising cash management, financial reporting and managing the day to day.</p>
            </div>
        </div>
        <div class="get-started-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <div class="left-content">
                            <h2>Ready to get started? Click here to schedule a free consultation.</h2>
                        </div>
                    </div>
                    <div class="col-md-3 justify-content-end">
                        <div class="right-button">
                            <a href="#">Click Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="browse-plans">
            <div class="container">
                <div class="title">
                    <h2>Browse Business Plan/Idea
                </div>
                <div class="member-list">
                    <div class="card">
                        <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                            <div class="media-left">
                                <a href="#" class="profile-image">
                                    <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="" class="w-100">
                                </a>
                            </div>
                            <div class="member-detail">
                                <h2 class="name">Nida Yasir</h2>
                                <div class="skills">
                                    <label>Skills</label>
                                    <p>Marketing, Public Relations</p>
                                </div>
                                <div class="location">
                                    <label>City</label>
                                    <p>Ahmedabad</p>
                                </div>
                            </div>
                            <div class="contact-details">
                                <ul>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Message</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                            <div class="media-left">
                                <a href="#" class="profile-image">
                                    <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="" class="w-100">
                                </a>
                            </div>
                            <div class="member-detail">
                                <h2 class="name">Nida Yasir</h2>
                                <div class="skills">
                                    <label>Skills</label>
                                    <p>Marketing, Public Relations</p>
                                </div>
                                <div class="location">
                                    <label>City</label>
                                    <p>Ahmedabad</p>
                                </div>
                            </div>
                            <div class="contact-details">
                                <ul>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Message</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                            <div class="media-left">
                                <a href="#" class="profile-image">
                                    <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="" class="w-100">
                                </a>
                            </div>
                            <div class="member-detail">
                                <h2 class="name">Nida Yasir</h2>
                                <div class="skills">
                                    <label>Skills</label>
                                    <p>Marketing, Public Relations</p>
                                </div>
                                <div class="location">
                                    <label>City</label>
                                    <p>Ahmedabad</p>
                                </div>
                            </div>
                            <div class="contact-details">
                                <ul>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Message</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                            <div class="media-left">
                                <a href="#" class="profile-image">
                                    <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="" class="w-100">
                                </a>
                            </div>
                            <div class="member-detail">
                                <h2 class="name">Nida Yasir</h2>
                                <div class="skills">
                                    <label>Skills</label>
                                    <p>Marketing, Public Relations</p>
                                </div>
                                <div class="location">
                                    <label>City</label>
                                    <p>Ahmedabad</p>
                                </div>
                            </div>
                            <div class="contact-details">
                                <ul>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Message</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection