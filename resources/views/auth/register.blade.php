@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-wrap login-wrap">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5 register-form">
            <div class="card">
                <div class="card-header">{{ __('Welcome! to MEA.') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" name="SignupForm" class="SignupForm">
                        @csrf
                        <div class="form-group unique-radio">
                            <div class="form-radio-group">
                                <label class="radio-inline active">
                                    <i class="flaticon-employee"></i>
                                    <input type="radio" class="" name="user_type" value="2" checked="">User
                                </label>
                                <label class="radio-inline">
                                    <i class="flaticon-entrepreneur"></i>
                                    <input type="radio" class="" name="user_type" value="3">Entrepreneur
                                </label>
                            </div>   
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" placeholder="Name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="Email Id">

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="form-group action-wrap row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>   
                                <span>Or</span>
                                <a href="{{ url('login/google') }}" style="margin-top: 20px;background:linear-gradient(-150deg, #4285f4, #34a853, #fbbc05, #ea4335);" class="btn btn-lg btn-success btn-block">
                                    <strong>Login With Google</strong>
                                </a>
                                <br />
                                <a href="{{ url('login/facebook') }}" style="background-color: #4267B2" class="btn btn-lg btn-warning btn-block">
                                    <strong>Login With Facebook</strong>
                                </a>
                                <div class="login-link text-right">
                                    <p>Already a MEA member? <a href="{{ route('login') }}">Login</a></p>
                                </div>
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
<script type="text/javascript" src="{{ Helper::assets('js/pages/account/register.js') }}"></script>
<script type="text/javascript">
    var base_url = "{{ url('/') }}/";
</script>

@endsection