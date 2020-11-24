@extends('admin.app-admin')
@section('title') Change password @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                <span class="breadcrumb-item active">Change Password</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
		<form action="{{ route('admin.update-password') }}" method="post" class="form-horizontal form-validate" autocomplete="off">
		 	@csrf
			<div class="m-auto col-lg-4 col-md-6 col-sm-12">
				<div class="form-group row">
					<label class="col control-label">Current Password <span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="password" class="form-control" name="current_password" id="current_password" autofocus="autofocus" value="{{ old('current_password') }}">
						@if ($errors->has('current_password'))
	                        <span class="help-block text-danger">
	                            <i class="icon-cancel-circle2 position-left"></i>{{ $errors->first('current_password') }}
	                        </span>
	                    @endif
					</div>
				</div>
				<div class="form-group row">
					<label class="col control-label">New Password <span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}">
						@if ($errors->has('password'))
	                        <span class="help-block text-danger">
	                            <i class="icon-cancel-circle2 position-left"></i>{{ $errors->first('password') }}
	                        </span>
	                    @endif
					</div>
				</div>
				<div class="form-group row">
					<label class="col control-label">Confirm New Password <span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}">
						@if ($errors->has('password_confirmation'))
	                        <span class="help-block text-danger">
	                            <i class="icon-cancel-circle2 position-left"></i>{{ $errors->first('password_confirmation') }}
	                        </span>
	                    @endif
					</div>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-primary rounded-round">Change Password</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
@section('footer_content')
	<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/additional_methods.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/pages/account/changepassword.js') }}"></script>
@endsection
