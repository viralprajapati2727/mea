@extends('admin.app-admin')
@section('title') Messages @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.email-subscriptions') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Messages</a>
                <span class="breadcrumb-item active">Messages</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Messages</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_resource"><i class="icon-plus2"></i> Send Email</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <div class="event-search-icon">
                        <i class="flaticon-search"></i>
                    </div>
                    <label class="text-secondary">Search</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-4 padding-top-class pt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state userlListDatabase" id="datatable">
        <thead>
            <tr>
                <th>Group</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>

<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
    var userTable = "";
    var filter = "{{ route('admin.messages-filter') }}";
    
$(document).ready(function () {
    userTable = $('#datatable').DataTable({
        serverSide: true,
        bFilter: false,
        ajax: {
            url: filter,
            type: 'POST',
            beforeSend: function () {
                $('body').block({
                    message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.15,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            },
            data: function (d) {
                d.keyword = $('#keyword').val();
            },
            complete: function () {
                $('body').unblock();
            },
        },
        columns: [
            { data: 'group', name: 'group', searchable: false },
            { data: 'created_by', name: 'created_by', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            // { data: 'status', name: 'status', searchable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false }
        ]
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });
});

</script>
@endsection
