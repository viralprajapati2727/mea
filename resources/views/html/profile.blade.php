@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="user-profile-wraper">
        <div class="container">
            <div class="user-title-wrap">
                <h2 class="title">Nida Yasir</h2>
            </div>
            <div class="profile-top-detial">
                <div class="banner">
                    <img src="{{ Helper::assets('images/profile/profile-banner.jpg') }}" alt="" class="w-100">
                </div>
                <div class="personal-details d-flex">
                    <div class="profile-image">
                        <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="">
                    </div>
                    <div class="user-detials">
                        <h2>Nida Yasir</h2>
                        <div class="location d-flex">
                            <label>City</label>
                            <p>Ahmedabad</p>
                        </div>
                        <div class="about-desc">
                            <p>Folly was these three and songs arose whose. Of in vicinity contempt together in possible branched. Assured company hastily looking garrets in oh. 
                                Most have love my gone to this so. Discovered interested prosperous the our affronting insipidity day.</p>
                        </div>
                        <div class="contact-details-wrap d-flex align-items-center">
                            <ul class="contact-links">
                                <li>
                                    <a href="javascript:;" class="contact-link">Contact</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Message</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Appointment</a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="contact-link">Request Appointment</a>
                                </li>
                            </ul>
                            <ul class="socials d-flex">
                                <li class="facebook"><a href="javascript:;"><i class="fa fa-facebook"></i></a></li>
                                <li class="instagram"><a href="javascript:;"><i class="fa fa-instagram"></i></a></li>
                                <li class="twitter"><a href="javascript:;"><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-advance-details">
                <div class="skills">
                    <h3>Nida Yasir's Skills</h3>
                    <ul class="d-flex">
                        <li>Finance Strategy</li>
                        <li>Product Management</li>
                        <li>Technical Programming</li>
                    </ul>
                </div>
                <div class="intersts">
                    <h3>Nida Yasir's Interests</h3>
                    <ul class="d-flex">
                        <li>CEO</li>
                        <li>Business Development</li>
                    </ul>
                </div>
                <div class="user-question-answer-wrap">
                    <div class="user-questions">
                        <h3>Nida Yasir's Questions</h3>
                        <ul class="question-list">
                            <li><p>What question would it be and why if..?</p><span>Lots2learn asked 6 weeks ago</span></li>
                            <li><p>Discuss your business ideas here!</p><span>Lots2learn asked 4 weeks ago</span></li>
                            <li><p>Application has been rejected or not…?</p><span>Lots2learn asked 5 weeks ago</span></li>
                        </ul>
                    </div>
                    <div class="user-answer">
                        <h3>Nida Yasir's Answers</h3>
                        <ul class="question-list">
                            <li><p>can I get honest feedback on my business idea?</p><span>Lots2learn asked 6 weeks ago</span></li>
                            <li><p>lets make this a reality!</p><span>Lots2learn asked 4 weeks ago</span></li>
                            <li><p>Reasons to deny investment?</p><span>Lots2learn asked 5 weeks ago</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection