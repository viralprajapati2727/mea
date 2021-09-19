@extends('admin.app-admin')
@section('title') Chat List @endsection
@section('page-header')
@php
$statuss = config('constant.job_status');
@endphp
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <span class="breadcrumb-item active">Chat List</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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