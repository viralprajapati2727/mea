<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 footer-col-wrap">
                <div class="footer-col">
                    <h3 class="f-title">Company</h3>
                    <ul class="footer-links"> 
                        <li><a href="{{ route('page.about-us') }}">About</a></li>
                        {{-- <li><a href="{{ route('page.team') }}">Team</a></li> --}}
                        <li><a href="{{ route('page.contact-us') }}">Contact</a></li>
                        <li><a href="{{ route('page.faq') }}">FAQ</a></li>
                        {{-- <li><a href="javascript:;">Career opportunities</a></li> --}}
                    </ul>   
                </div>
            </div>
            <div class="col-sm-4 footer-col-wrap"> 
                <div class="footer-col">
                <h3 class="f-title">Community</h3>
                    <ul class="footer-links">
                        {{-- <li><a href="javascript:;">Pricing</a></li> --}}
                        {{-- <li><a href="javascript:;">Member benefits</a></li> --}}
                        <li><a href="{{ route('page.questions') }}">Questions</a></li>
                        <li><a href="{{ route('page.blogs') }}">Blog</a></li>
                    </ul>     
                </div>
            </div>
            <div class="col-sm-4 footer-col-wrap">
                <div class="footer-col">
                    <h3 class="f-title"> <img src="{{ Helper::assets('images/logo.png') }}" class="logo" alt=""> Register for MEA updates</h3>
                    <div class="newsletter-wrap">
                        <form action="{{ route('subscribe-email') }}" method="POST">
                            <div class="form-control">
                                @csrf
                                <input type="email" name="email" placeholder="Email" required />
                                <button type="submit" class="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                    <div class="footer-socials">
                        <ul>
                            {{-- <li>
                                <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                            </li> --}}
                            <li>
                                <a href="https://www.facebook.com/MEA.Global.Association" target="_blank"><i class="fa fa-facebook"></i></a>
                            </li>
                            {{-- <li>
                                <a href="javascript:;"><i class="fa fa-linkedin"></i></a>
                            </li> --}}
                            <li>
                                <a href="https://www.instagram.com/mea_network/" target="_blank"><i class="fa fa-instagram"></i></a>
                            </li>
                            {{-- <li>
                                <a href="javascript:;"><i class="fa fa-google"></i></a>
                            </li>--}}
                            <li>
                                <a href="https://www.youtube.com/channel/UCb9Gu90KunkdKZ6vIqVErmw" target="_blank"><i class="fa fa-youtube-play"></i></a>
                            </li> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/main/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/main/jquery.form.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ Helper::assets('js/pages/authentication.js') }}"></script> --}}
@append

