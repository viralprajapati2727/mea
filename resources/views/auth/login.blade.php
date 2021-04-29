@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-wrap login-wrap">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5 login-form">
            <div class="card">
                <div class="card-header">{{ __('Welcome again, Please login.') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="SigninForm">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Id" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 d-flex justify-content-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                <div class="bottom-links d-flex justify-content-between">
                                    <p>New user?<a href="{{ route('register') }}">Register</a></p>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif

                                </div>
                                <a href="{{ url('login/google') }}" style="margin-top: 20px;background:linear-gradient(-150deg, #4285f4, #34a853, #fbbc05, #ea4335);" class="btn btn-lg btn-success btn-block">
                                    <strong>Login With Google</strong>
                                </a>
                                <br />
                                <a href="{{ url('login/facebook') }}" style="background-color: #4267B2" class="btn btn-lg btn-warning btn-block">
                                    <strong>Login With Facebook</strong>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('footer_script')  
<script type="text/javascript" src="{{ Helper::assets('js/pages/account/login.js') }}"></script>
@endsection
