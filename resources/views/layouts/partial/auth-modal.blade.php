<div id="sign-in-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-lg">
        <div class="modal-content">
            <div class="modal-body sign-in-modal-body">
                <form class="auth-validation modalform-reset" method="POST" action="{{ route('login') }}" autocomplete="off" name="loginform" id="loginform">
                        @csrf
                        @method('POST')
                    <input type="hidden" name="authtype" value="login">
                    <div class="text-center mb-1 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">Log In </h1>
                    </div>
                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-5 border-class d-flex justify-content-center d-md-block">
                                <div class="socialbutton-group text-center mt-3">
                                    <div class="form-group">
                                        <a href="{{ url('login/facebook') }}" class=" social-btn btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor facebook-shadowcolor">
                                            <i class="flaticon-logo pr-2 mr-3"></i> CONNECT WITH FACEBOOK</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('login/apple') }}" class=" social-btn  btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor bg-apple">
                                            <i class="flaticon-logo-4 pr-0 mr-0"></i>  SIGN IN WITH APPLE</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('login/google') }}" class=" social-btn  btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor bg-google" >
                                            <i class="flaticon-google pr-0 mr-0"></i>  SIGN IN WITH GOOGLE</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="sign-up-form mt-3">
                                    <div class="form-group col-md-12">
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="text" class="form-control input-text subscribe-input " name="email" required="required" id="loginemail" value="{{ old('email') }}" autocomplete="nope">
                                            <span class="floating-label">Email</span>
                                            @if ($errors->has('email'))
                                                <span id="loginemail-error" class="validation-error-label server-error">{{ $errors->first('email') }}</span>
                                            @endif
                                            <div class="subscribe-send-icon">
                                                <i class="flaticon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="password" class="form-control input-text subscribe-input " name="password" required="required" id="loginpassword" maxlength="16" autocomplete="nope">
                                            <span class="floating-label">Password</span>
                                            @if ($errors->has('password'))
                                                <span id="loginpassword-error" class="validation-error-label server-error">{{ $errors->first('password') }}</span>
                                            @endif
                                            <div class="password-icon">
                                                <i toggle="" class="flaticon-eye fa fa-lg field-icon toggle-password"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 text-right">
                                        <a href="javascript.void(0)" id="forgot-pwd" class="forgot-pwd-remove resend_link text-common-color font-product_sans_mediumregular font-medium popuphide" data-toggle="modal" data-target="#forgot-password-in-modal">Forgot Password?</a>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center pb-3">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg focus-none "><i class="flaticon-user-2 mr-1"></i>
                                            SIGN IN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
                <p class="text-custom-muted pt-2 pt-md-4 pt-lg-4 text-center">By Continuing, I accept Dancero's <span class="text-common-color"><a href="{{ route('terms-and-condition') }}" class="text-common-color font-product_sans_mediumregular font-medium focus-none">Terms of Service</a></span> and have read <span class="text-common-color"><a href="{{ route('privacy-policy') }}" class="text-common-color font-product_sans_mediumregular font-medium focus-none"> Privacy Policy </a></span></p>
            </div>
        </div>
    </div>
</div>

<div id="sign-up-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-lg">
        <div class="modal-content">
            <div class="modal-body sign-up-modal-body">
                <form class="auth-validation sign-up-modal-uniform modalform-reset" method="POST" action="{{ route('register') }}" autocomplete="off" name="signupform" id="signupform">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="authtype" value="register">
                    <div class="text-center mb-3 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">Sign Up</h1>
                    </div>

                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-5 border-class d-flex justify-content-center d-block">
                                <div class="socialbutton-group mt-3">
                                    <div class="form-group">
                                        <a href="{{ url('login/facebook') }}" class=" social-btn btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor  facebook-shadowcolor">
                                            <i class="flaticon-logo pr-2 mr-3"></i> CONNECT WITH FACEBOOK</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('login/apple') }}" class=" social-btn  btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor bg-apple">
                                            <i class="flaticon-logo-4 pr-0 mr-0"></i>  SIGN IN WITH APPLE</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('login/google') }}" class=" social-btn  btn member-login-btn  text-white rounded-lg font-socialbutton facebookcolor bg-google">
                                            <i class="flaticon-google pr-0 mr-0"></i>  SIGN IN WITH GOOGLE</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="sign-up-form mt-3">
                                    <div class="form-group col-md-12 ">
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="text" class="form-control input-text subscribe-input" name="email" id="email" required="required" value="{{ old('email') }}" autocomplete="nope">
                                            <span class="floating-label">Email</span>
                                            <div class="subscribe-send-icon">
                                                <i class="flaticon-mail"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('email'))
                                            <label id="email-error" class="validation-error-label">{{ $errors->first('email') }}</label>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="password" class="form-control input-text subscribe-input " name="password" id="password" required="required" value="" maxlength="16" autocomplete="nope">
                                            <span class="floating-label">Password</span>
                                            <div class="password-icon">
                                                <i toggle="" class="flaticon-eye fa fa-lg field-icon toggle-password"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <label id="signup-password" class="validation-error-label">{{ $errors->first('password') }}</label>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="password" class="form-control subscribe-input " id="password_confirmation" name="password_confirmation" required="required" value="" maxlength="16" autocomplete="nope">
                                            <span class="floating-label">Confirm Password</span>
                                            <div class="password-icon">
                                                <i toggle="" class="flaticon-eye fa fa-lg field-icon toggle-password"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <label id="password_confirmation-error" class="validation-error-label">{{ $errors->first('password_confirmation') }}</label>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="mx-auto mb-0 mb-md-3 mb-lg-3">
                                            <div class="form-check mx-3">
                                                <label class="form-check-label text-custom-muted">
                                                    <input type="checkbox" @if(old('is_professional') == 1) checked @endif class="form-check-input-styled this_professional" name="is_professional" value='1'>
                                                    I am a Professional (DJ, Organiser, Coach, Artist etc)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 text-right">
                                        <a href="javascript:void(0);" class="resend_link text-common-color font-medium font-product_sans_mediumregular popuphide"
                                           data-toggle="modal"  data-target="#resend-email-in-modal" id="resend-email">Resend Activation Link</a>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center py-2">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-4 rounded-lg"><i class="flaticon-lock mr-2"></i>GET STARTED</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="submit" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
                <p class="text-custom-muted pt-0 pt-md-4 pt-lg-4 text-center">By Continuing, I accept Dancero's <span class="text-common-color"><a href="{{ route('terms-and-condition') }}" class="text-common-color font-product_sans_mediumregular font-medium focus-none">Terms of Service</a></span> and have read <span class="text-common-color"><a href="{{ route('privacy-policy') }}" class="text-common-color font-product_sans_mediumregular font-medium focus-none"> Privacy Policy </a></span></p>
            </div>
        </div>
    </div>
</div>
<div id="resend-email-in-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-md">
        <div class="modal-content">
            <div class="modal-body sign-up-modal-body">
                <form class="auth-validation modalform-reset" method="POST" action="{{ url('email/resend  ') }}" autocomplete="off" name="resend_email">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="authtype" value="resend">
                    <div class="text-center mb-1 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">Resend Email </h1>
                    </div>
                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="resend-email-form mt-3">
                                    <div class="form-group">
                                        <!-- <label class="form-control-label">Email</label> -->
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="text" class="form-control input-text subscribe-input " name="email" id="resendemail" required value="{{ old('email') }}" autocomplete="nope">
                                            <span class="floating-label">Email</span>
                                            @if ($errors->has('email'))
                                                <span id="loginemail-error" class="validation-error-label server-error">{{ $errors->first('email') }}</span>
                                            @endif
                                            <div class="subscribe-send-icon">
                                                <i class="flaticon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center pt-3">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-4 rounded-lg"><i class="flaticon-send mr-2"></i>
                                            SEND REQUEST</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
                <p class="text-custom-muted pt-4 mb-0 text-center">Remember Password? <span class="text-common-color"><a href="javascript.void(0)" class="resend-remember-pwd text-common-color font-product_sans_mediumregular focus-none" data-toggle="modal" data-target="">Sign In</a></span></p>
            </div>
        </div>
    </div>
</div>

<div id="forgot-password-in-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-md">
        <div class="modal-content">
            <div class="modal-body sign-up-modal-body">
                <form class="auth-validation modalform-reset" method="POST" action="{{ route('password.email') }}" autocomplete="off" name="forgot_password" id="forgot_password">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="authtype" value="reset">
                    <div class="text-center mb-0 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">Reset Password </h1>
                    </div>
                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="forgot-password-form mt-3">
                                    <div class="form-group col-md-12">
                                        <!-- <label class="form-control-label">Email</label> -->
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="text" class="form-control input-text subscribe-input " name="email" id="forgot-email" value="{{ old('email') }}" required autocomplete="nope">
                                            <span class="floating-label">Email</span>
                                            @if ($errors->has('email'))
                                                <span id="loginemail-error" class="validation-error-label server-error">{{ $errors->first('email') }}</span>
                                            @endif
                                            <div class="subscribe-send-icon">
                                                <i class="flaticon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center pt-3">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-4 rounded-lg"><i class="flaticon-send mr-2"></i>
                                            SEND REQUEST</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
                <p class="text-custom-muted pt-4 mb-0 text-center">Remember Password? <span class="text-common-color"><a href="javascript.void(0)" class="remember-pwd text-common-color font-product_sans_mediumregular focus-none" data-toggle="modal" data-target="">Sign In</a></span></p>
            </div>
        </div>
    </div>
</div>

<div id="set-password-in-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-md">
        <div class="modal-content">
            <div class="modal-body sign-up-modal-body">
                <form class="auth-validation modalform-reset" method="POST" action="{{ route('password.update') }}" autocomplete="off" name="set_password" id="set_password">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="authtype" value="setnewpassword">
                    @if(isset($token))
                        <input type="hidden" name="token" value="{{ $token }}">
                    @endif
                    <div class="text-center mb-0 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">Reset Password </h1>
                    </div>
                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="forgot-password-form mt-3">
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label">Email</label>
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="text" class="form-control subscribe-input " value="{{ $email ?? old('email') }}" name="email" placeholder="Email" id="set-new-email" readonly>
                                            @if ($errors->has('email'))
                                                <span id="loginemail-error" class="validation-error-label server-error">{{ $errors->first('email') }}</span>
                                            @endif
                                            <div class="subscribe-send-icon">
                                                <i class="flaticon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <!-- <label class="form-control-label">Password</label> -->
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="password" class="form-control subscribe-input " name="password" id="f_password" placeholder="Password" required="required" value="" maxlength="16" autocomplete="nope">
                                            <div class="password-icon">
                                                <i toggle="" class="flaticon-eye fa fa-lg field-icon toggle-password"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('f_password'))
                                            <label id="f_password-error" class="validation-error-label">{{ $errors->first('f_password') }}</label>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <!-- <label class="form-control-label">Confirm Password</label> -->
                                        <div class="position-relative mt-lg-0 stop-drag">
                                            <input type="password" class="form-control subscribe-input " id="signup-password_confirmation" name="password_confirmation"  placeholder="Confirm Password" required="required" value="" maxlength="16" autocomplete="nope">
                                            <div class="password-icon">
                                                <i toggle="" class="flaticon-eye fa fa-lg field-icon toggle-password"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <label id="signup-password_confirmation-error" class="validation-error-label">{{ $errors->first('password_confirmation') }}</label>
                                        @endif
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-center pt-3">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg focus-none "><i class="flaticon-user-2 mr-1"></i>
                                            RESET</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="select-role-modal" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered  signup_popup modal-md">
        <div class="modal-content">
            <div class="modal-body sign-up-modal-body">
                <form class="auth-validation modalform-reset" method="POST" action="{{ route('continueAfterSocialRegister') }}" autocomplete="off" name="continueAfterSocialRegister">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="authtype" value="social_register">
                    @if(isset($social) && isset($provider_id))
                        <input type="hidden" name="name" value="{{ $name }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="social" value="{{ $social }}">
                        <input type="hidden" name="provider_id" value="{{ $provider_id }}">
                    @endif
                    <div class="text-center mb-1 mb-md-4 mb-lg-4">
                        <h1 class="section-title mb-0 text-black">I want to continue as </h1>
                    </div>
                    <div class="sign-up-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="resend-email-form mt-3">
                                    <div class="form-check mx-3">
                                        <label class="form-check-label text-custom-muted">
                                            <input type="radio" class="form-check-input-styled" name="is_professional" value='1'>
                                            Professional (DJ, Organiser, Coach, Artist etc)
                                        </label>
                                    </div>
                                    <div class="form-check mx-3">
                                        <label class="form-check-label text-custom-muted">
                                            <input type="radio" class="form-check-input-styled" name="is_professional" value='0' checked>
                                            Dancer
                                        </label>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center pt-3">
                                        <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-4 rounded-lg"><i class="flaticon-lock mr-2"></i>GET STARTED</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="jb_close_btn">
                        <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                            <i class="flaticon-close"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
