@extends('admin.app-admin')

@section('content')
    <!-- Login form -->
    <form class="login-form" method="POST" action="{{ route('admin.login') }}" autocomplete="off">
        @csrf
        <div class="text-center">
            <a href="{{ url('/') }}" class="d-inline-block mb-2">
                <img src="{{ Helper::assets('images/logo.png') }}" alt="logo" height="150" width="150">
            </a>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h5 class="mb-0">Admin Login</h5>
                    <span class="d-block text-muted">Enter your credentials below</span>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('err_status'))
                    <div class="alert alert-danger">
                        {{ session('err_status') }}
                    </div>
                @endif

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="form-control-feedback">
                        <i class="icon-envelope text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>
                <div class="form-group d-flex align-items-center">
                    <div class="form-check mb-0">
                        <label class="form-check-label">
                            <div class="uniform-checker">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="form-input-styled">
                            </div>
                            Remember
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('admin.password.request') }}" class="ml-auto">Forgot Your Password?</a>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
                </div>
            </div>
        </div>
    </form>
    <!-- /login form -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.form-input-styled').uniform();
        $('.content').addClass('d-flex justify-content-center align-items-center');
    })
</script>
@endsection
