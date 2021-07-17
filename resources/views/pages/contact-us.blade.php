@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner contact-us">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1>Contact us</h1>
                    <p>We want to hear from you - Today!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="contact-outer-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7">
                        <div class="contact-form-wraper">
                            <div class="contact-form-inner">
                                <div class="contact-heading">
                                    <h2>Send Us a Message</h2>
                                </div>
                                <form class="contact-form" method="post" action="{{ route('contact-us-request') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Subject Line" name="subject" id="subject">
                                    </div>
                                    <div class="form-group">
                                        <textarea rows="5" cols="5" class="form-control" placeholder="Message" name="message" id="message"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-light btn-block jb_btn">SEND</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
                    <div class="contact-right">
                        <div class="contact-detail">
                            <h3 class="title">Contact Information</h3>
                            <div class="contact_detail_icons">
                                <div class="media">
                                    <div class="contact-icon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <div class="contact-dtl-body">
                                        <address>
                                            New York, NY
                                        </address>
                                    </div>
                                </div>
                                <div class="media">
                                    <div class="contact-icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="contact-dtl-body">
                                        <a href="tel: 516-308-6498">516-308-6498</a>
                                    </div>
                                </div>
                                <div class="media">
                                    <div class="contact-icon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="contact-dtl-body">
                                        <a href="mailto: Info@MEA-Community.com ">Info@MEA-Community.com </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contact-socials">
                            <ul>
                                {{-- <li>
                                    <a href="javascript:;" class="twitter"><i class="fa fa-twitter"></i></a>
                                </li> --}}
                                <li>
                                    {{-- <a href="javascript:;" class="facebook"><i class="fa fa-facebook"></i></a> --}}
                                    <a href="https://www.facebook.com/MEA.Global.Association" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a>
                                </li>
                                {{-- <li>
                                    <a href="javascript:;" class="linkedin"><i class="fa fa-linkedin"></i></a>
                                </li> --}}
                                <li>
                                    <a href="https://www.instagram.com/mea_network/" target="_blank" class="insta"><i class="fa fa-instagram"></i></a>
                                </li>
                                {{-- <li>
                                    <a href="javascript:;" class="google"><i class="fa fa-google"></i></a>
                                </li> --}}
                                <li>
                                    <a href="https://www.youtube.com/channel/UCb9Gu90KunkdKZ6vIqVErmw" target="_blank" class="youtube"><i class="fa fa-youtube-play"></i></a>
                                </li>
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
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/pages/contactform.js') }}"></script>
@endsection