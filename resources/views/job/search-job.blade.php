@extends('layouts.app')
@section('content')

<div class="job-portal-main">
    <div class="jp-front-banner">
        <div class="container">
            <div class="jp-banner-content text-center">
                <h1>Find Your Desire Job</h1>
                <p>Jobs, Employment & Future Career Opportunities</p>
            </div>
            <form class="global-search-form" action="{{ route('job.global-search') }}">
                <div class="form-group">
                    <div class="form-body">
                        <div class="input-control">
                            <input type="text" name="title" placeholder="Job TItle">
                        </div>
                        <div class="input-control">
                            <input type="text" name="city[]" placeholder="City">
                        </div>
                        <div class="input-control">
                            <select name="category[]">
                                <option value="">Job Category</option>
                                @forelse ($business_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="form-btn">Serch</button>
                    </div>
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
                    @php
                        // $bCategories = $business_categories->limit(12)->all();
                    @endphp
                    @forelse ($business_categories as $category)
                    @php
                        // echo "<pre>"; print_r($category); echo "</pre>";
                    @endphp
                        <li class="text-center jp-job-cat-lwrp">
                            <a href="{{ route('job.global-search') }}?category%5B%5D={{ $category->id }}">
                                <div class="jp-job-cat-icon">
                                    @php
                                        $bcategoryUrl = Helper::images(config('constant.business_category_url'));
                                    @endphp
                                    <img src="{{ $bcategoryUrl.$category->src }}" alt="">
                                </div>
                                <h5 class="jp-job-cat-title">{{ $category->title }}</h5>
                            </a>
                        </li>
                    @empty
                    @endforelse
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