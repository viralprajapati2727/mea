@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="page-wraper">
        <div class="job-top-details">
            <div class="container">
                <div class="job-inner-wrap">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="job-details-left">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="job-profile">
                                            <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="" class="w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="job-details-inner">
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
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="job-details-right d-flex justify-content-end">
                                <a href="#" class="apply-btn">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="job-wrap job-description-wrap">
                    <h2 class="title">Job Description</h2>
                    <div class="text">
                        <p>The project "Acceptance & value based equipment checking, equipment GPS Support team & Remote Mobile Check Ins". The system will be a web application along with a mobile application covering all the business aspects for any company that involves in Instrument production, delivery and support facilities. Using the web application the admin can check the correct location of the member of the support staff, the status of complains ,the complain history (graph), the status of instrument from plain to delivery. using the mobile application the location of support team can be traced using GPS, daily task list allocation and access in individual for members of support team, work updates and real time photo capturing and updation on website.</p>
                    </div>
                </div>
                <div class="job-wrap job-details">
                    <h2 class="title">Job Details</h2>
                    <div class="job-detail-list">
                        <label class="lable">Industry:</label>
                        <p>Sales</p>
                    </div>
                    <div class="job-detail-list">
                        <label class="lable">Skills:</label>
                        <ul class="skills">
                            <li>Marketing</li>
                            <li>Public Relations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 