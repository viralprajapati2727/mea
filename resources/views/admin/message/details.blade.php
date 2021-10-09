@extends('admin.app-admin')
@section('title') Chat List @endsection
@section('page-header')
@php
$statuss = config('constant.job_status');
@endphp
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex justify-content-space-between w-100">
            <div class="breadcrumb">
                <span class="breadcrumb-item active">Chat List</span>
            </div>
            <a href="{{ route('admin.message.download',['type' => 'pdf', 'id' => base64_encode($group_id)]) }}" class="download-link text-default text-right pr-5"><i class="icon-file-pdf" style="color: red"></i> Download pdf</a>
        </div>
    </div>
</div>
@endsection
@section('content')
<h6 class="card-title text-center">Chat List</h6>
<div class="page-main p-0">
    <div class="page-wraper">
        <div class="quetions-lists-wraper">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="message-list">
                            @include('admin.message.message-list')
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_content')
<script> 
    
</script>
@endsection