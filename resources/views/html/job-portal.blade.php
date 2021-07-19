@extends('layouts.app')
@section('content')

<div class="job-portal-main">
    <div class="jp-front-banner">
        <div class="container">
            <div class="jp-banner-content text-center">
                <h1>Find Your Desire Job</h1>
                <p>Jobs, Employment & Future Career Opportunities</p>
            </div>
            <form class="search-form">
                <div class="form-group">

                </div>
            </form>
        </div>
    </div>
    <div class="jp-job-category">
        <div class="container">
            <div class="title">
                <h2>Choose Your Desire Category</h2>
            </div>
            <div class="jp-job-category-listing">
                <ul class="m-auto">
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/technical-support.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Technical Support</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/business-development.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Business Development</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/real-estate.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Real Estate Business</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/share-market-analysis.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Share Market Analysis</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/weather.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Weather & Environment</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/finance.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Finance & banking Service</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/networking.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">IT & Networking Services</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/restaurant.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Restaurant Services</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/fire.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">Defence & Fire Service</h5>
                        </a>
                    </li>
                    <li class="text-center jp-job-cat-lwrp">
                        <a href="#">
                            <div class="jp-job-cat-icon">
                                <img src="{{ Helper::assets('images/business-category/delivery.png') }}" alt="">
                            </div>
                            <h5 class="jp-job-cat-title">delivery</h5>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="recent-jobs">
        <div class="container">
            <div class="title text-center">
                <h2>Recent Jobs</h2>
                <p>Make the most of the opportunity available by browsing among the most trending categories and get hired today.</p>
            </div>
            <div class="job-lists-wrap">
                <div class="card">
                    <div class="jobs-details-wrap">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <div class="job-detail-left">
                                    <div class="job-media">
                                        <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="">
                                    </div>
                                    <div class="job-description">
                                        <h2 class="job-title"><a href="#">Web designer</a></h2>
                                        <div class="job-company-location">
                                            <p class="company">@ Company A</p>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-map-marker"></i>
                                            <span>San Francisco</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-clock-o"></i>
                                            <span>Full Time</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block">
                                            <i class="fa fa-money"></i>
                                            <span>$ 1,500</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="job-actions">
                                    <ul>
                                        <li><a href="#" class="job-detail-btn">Job Detail</a></li>
                                        <li><a href="#" class="apply-btn">Quick Apply</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="jobs-details-wrap">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <div class="job-detail-left">
                                    <div class="job-media">
                                        <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="">
                                    </div>
                                    <div class="job-description">
                                        <h2 class="job-title"><a href="#">Web designer</a></h2>
                                        <div class="job-company-location">
                                            <p class="company">@ Company A</p>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-map-marker"></i>
                                            <span>San Francisco</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-clock-o"></i>
                                            <span>Full Time</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block">
                                            <i class="fa fa-money"></i>
                                            <span>$ 1,500</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="job-actions">
                                    <ul>
                                        <li><a href="#" class="job-detail-btn">Job Detail</a></li>
                                        <li><a href="#" class="apply-btn">Quick Apply</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="jobs-details-wrap">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <div class="job-detail-left">
                                    <div class="job-media">
                                        <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="">
                                    </div>
                                    <div class="job-description">
                                        <h2 class="job-title"><a href="#">Web designer</a></h2>
                                        <div class="job-company-location">
                                            <p class="company">@ Company A</p>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-map-marker"></i>
                                            <span>San Francisco</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-clock-o"></i>
                                            <span>Full Time</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block">
                                            <i class="fa fa-money"></i>
                                            <span>$ 1,500</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="job-actions">
                                    <ul>
                                        <li><a href="#" class="job-detail-btn">Job Detail</a></li>
                                        <li><a href="#" class="apply-btn">Quick Apply</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="jobs-details-wrap">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <div class="job-detail-left">
                                    <div class="job-media">
                                        <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="">
                                    </div>
                                    <div class="job-description">
                                        <h2 class="job-title"><a href="#">Web designer</a></h2>
                                        <div class="job-company-location">
                                            <p class="company">@ Company A</p>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-map-marker"></i>
                                            <span>San Francisco</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block mr-3">
                                            <i class="fa fa-clock-o"></i>
                                            <span>Full Time</span>
                                        </div>
                                        <div class="d-sm-inline d-inline-block">
                                            <i class="fa fa-money"></i>
                                            <span>$ 1,500</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="job-actions">
                                    <ul>
                                        <li><a href="#" class="job-detail-btn">Job Detail</a></li>
                                        <li><a href="#" class="apply-btn">Quick Apply</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </diV>
        </div>
    </div>
</div>

@endsection