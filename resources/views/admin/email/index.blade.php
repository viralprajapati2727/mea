@extends('admin.app-admin')
@section('title') Emails @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                <span class="breadcrumb-item active">Emails</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-0">Email List</h5>
    </div>
    <table class="table datatable-save-state" id="email_template">
		<thead>
			<tr>
				{{--  <th>sr. #</th>  --}}
				<th>Email ID</th>
				<th>Email Name</th>
				<th>Email Subject</th>
				<th>Date Updated</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			@php $count = 1; @endphp
			@foreach ($email as $key => $data)
              	<tr>
                    {{--  <td>{{ $count }}</td>  --}}
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->emat_email_name }}</td>
                    <td>{{ $data->emat_email_subject }}</td>
                    <td>{{ date("d-m-Y",strtotime($data->updated_at)) }}</td>
                    <td class="text-center"><a href="{{ route('emails.edit',$data->id) }}" title="Edit" class="edit"><i class="icon-pencil7 text-primary"></i></a></td>
              	</tr>
            @endforeach
        </tbody>
	</table>
</div>
@endsection
@section('footer_content')
	<script type="text/javascript">var is_form_edit = true;</script>
    <script type="text/javascript" src="{{ Helper::assets('js/pages/email_templates.js') }}"></script>
@endsection
