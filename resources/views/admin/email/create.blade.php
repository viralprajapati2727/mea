@extends('admin.app-admin')
@section('title') Email @endsection
@php 
	$is_update = false;
	if(isset($email)) {
		// dd($id);
		// echo "<pre>";print_r($email);exit;
		$is_update = true;
	}
@endphp
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                <a href="{{ route('emails.index') }}" class="breadcrumb-item"><i class="icon-envelop mr-2"></i> Emails</a>
                <span class="breadcrumb-item active">{{ $is_update ? 'Edit' : 'Add' }} Email</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header bg-white header-elements-inline">
		<h6><strong>Keywords: </strong>{{ implode(', ',config('constant.email_template_tag')) }}</h6>
	</div>
    <div class="card-body">
		<form action="{{ $is_update ? route('emails.update',$id) : route('emails.store') }}" method="post" class="form-horizontal form-validate" name="cartform">
			@csrf
			@if(isset($email))
				@method('PATCH')
			@endif
			<div class="col-md-12">
				@if($is_update)
				<input type="hidden" value="{{ base64_encode($email->id) }}" name="email_id">
				@endif
				<div class="form-group row">
					<label class="col-lg-3 col-form-label">Email Name <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="emat_email_name" id="emat_email_name" autofocus="autofocus" value="{{ $is_update ? $email->emat_email_name : '' }}">
						@if ($errors->has('emat_email_name'))
	                        <label class="validation-error-label">{{ $errors->first('emat_email_name') }}
	                        </label>
	                    @endif
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 col-form-label">Email Subject <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="emat_email_subject" id="emat_email_subject" value="{{ $is_update ? $email->emat_email_subject : '' }}">
						@if ($errors->has('emat_email_subject'))
	                        <label class="validation-error-label">{{ $errors->first('emat_email_subject') }}
	                        </label>
	                    @endif
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 col-form-label">Email Body <span class="text-danger">*</span></label>
					<div class="col-lg-9 ckeditor">
						<textarea name="emat_email_message" class="form-control ckeditor" id="emat_email_message" rows="10" cols="80">{{ $is_update ? $email->emat_email_message : '' }}</textarea>
						@if ($errors->has('emat_email_message'))
	                        <label class="validation-error-label">{{ $errors->first('emat_email_message') }}
	                        </label>
	                    @endif
					</div>
				</div>

				<div class="text-right">
					<button type="submit" class="btn btn-primary rounded-round">{{ $is_update ? 'Update' : 'Add' }}</button>
					<a href="{{ route('emails.index') }}"><button type="button" class="btn btn-danger rounded-round">Cancel</button></a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
@section('footer_content')
	<script type="text/javascript">var is_form_edit = true;</script>
    <script type="text/javascript" src="{{ Helper::assets('js/pages/email_templates.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" >
$(function () {
    CKEDITOR.replace('emat_email_message', {
        height: '300px',
        removeButtons: 'Subscript,Superscript,Image',
		toolbarGroups: [
            {name: 'styles'},
            {name: 'editing', groups: ['find', 'selection']},
            {name: 'basicstyles', groups: ['basicstyles']},
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align']},
            {name: 'links'},
            {name: 'insert'},
            {name: 'colors'},
            {name: 'tools'},
            {name: 'others'},
            {name: 'document', groups: ['mode', 'document', 'doctools']}
        ]
    });
});
</script>
@endsection