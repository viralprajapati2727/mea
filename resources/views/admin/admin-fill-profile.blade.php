@extends('admin.app-admin')
@section('title') Change password @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                <span class="breadcrumb-item active">My Profile</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
		<form action="{{ route('admin.store-profile') }}" method="post" class="form-horizontal form-validate" autocomplete="off" name="admin-profile-form" enctype="multipart/form-data">
		 	@csrf
			<div class="m-auto col-lg-4 col-md-6 col-sm-12">
				<div class="form-group row">
					<label class="col control-label">Profile Photo<span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="file" class="form-control" name="profile_photo" autofocus="autofocus" value="">
						<input type="hidden" name="old_logo" value="{{ $admin->logo }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col control-label">Name <span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="text" class="form-control" name="name" value="{{ isset($admin->name) ? $admin->name : "" }}">
						@if ($errors->has('name'))
	                        <span class="help-block text-danger">
	                            <i class="icon-cancel-circle2 position-left"></i>{{ $errors->first('name') }}
	                        </span>
	                    @endif
					</div>
				</div>
				<div class="form-group row">
					<label class="col control-label">Email <span class="text-danger">*</span></label>
					<div class="col-lg-12">
						<input type="text" class="form-control" name="email" value="{{ isset($admin->email) ? $admin->email : "" }}" readonly disabled>
					</div>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-primary rounded-round">Save</button>
					<span class="pl-md-3 d-block d-md-inline-block d-lg-inline-block pt-3 pl-1 pt-md-0 pt-lg-0"><a href="{{ route('admin.index') }}" class="text-color-blue font-weight-bold">CANCEL</a></span>
				</div>
			</div>
			
		</form>
	</div>
</div>
@endsection
@section('footer_content')
	<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/additional_methods.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ Helper::assets('js/pages/account/changepassword.js') }}"></script> --}}
@endsection