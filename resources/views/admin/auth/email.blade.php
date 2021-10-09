@extends('admin.app-admin')

@section('content')
    <!-- Login form -->
    <form class="login-form" method="POST" action="{{ route('admin.password.email') }}" autocomplete="off">
        @csrf
        <div class="text-center">
            <a href="{{ url('/') }}" class="d-inline-block">
                <a href="{{ route('index') }}" class="d-inline-block">
                    <img src="{{ Helper::assets('images/logo.png') }}" alt="" height="150" width="150">
                </a>
            </a>
        </div>
        <div class="card mb-0 mt-3">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h5 class="mb-0">Forgot Password</h5>
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

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
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
