<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-col">
                    <h3 class="f-title">Company</h3>
                    <ul class="footer-links">
                        <li><a href="javascript:;">About</a></li>
                        <li><a href="javascript:;">Team</a></li>
                        <li><a href="javascript:;">Contact</a></li>
                        <li><a href="javascript:;">Blog</a></li>
                        <li><a href="javascript:;">Career opportunities</a></li>
                    </ul>   
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-col">
                <h3 class="f-title">Community</h3>
                    <ul class="footer-links">
                        <li><a href="javascript:;">Pricing</a></li>
                        <li><a href="javascript:;">Member benefits</a></li>
                        <li><a href="javascript:;">Help</a></li>
                    </ul>     
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-col">
                    <h3 class="f-title">Register for MEA updates</h3>
                    <div class="newsletter-wrap">
                        <form>
                            <div class="form-control">
                                <input type="email" name="Email" placeholder="Email"/>
                                <button type="submit" class="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                    <div class="footer-socials">
                        <ul>
                            <li>
                                <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="fa fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="fa fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="fa fa-google"></i></a>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="fa fa-youtube-play"></i></a>
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

