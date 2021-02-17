@extends('admin.app-admin')
@section('title') Startup Portal @endsection
@section('page-header')
<!-- Page header -->
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.startup-portal.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Startup Portal</a>
                <span class="breadcrumb-item active">Startup Portal Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-2">
                <div class="controls">
                    <label class="text-secondary">Startup Name</label>
                    <input type="text" name="keyword" placeholder="Startup Name" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-3 mt-3">
                <label class="text-secondary"></label>
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state dataTable no-footer" role="grid" id="datatable">
        <thead>
            <tr>
                <th>Startup Name</th>
                <th>Startup industry</th>
                <th>Startup location</th>
                <th>Stage of startup</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@section('footer_content')
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
    var startupTable = "";
    var approve_reject_link = "{{ route('admin.startup.approve-reject') }}";

    var filter = "{{ route('admin.startup-filter') }}";

$(document).ready( function () {
    startupTable = $('#datatable').DataTable({
        serverSide: true,
        bFilter:false,
        ajax: {
            url: filter,
            type: 'POST',
            beforeSend: function(){
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
            complete: function(){
                $('body').unblock();
            },
        },
        columns: [
            { data: 'name', name: 'name' ,searchable:false, orderable:false},
            { data: 'industry', name: 'industry' ,searchable:false, orderable:false},
            { data: 'location', name: 'location' ,searchable:false, orderable:false},
            { data: 'stage_of_startup', name: 'stage_of_startup' ,searchable:false},
            { data: 'action', name: 'action', searchable:false, orderable:false }
        ]
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#job_title_id').val('').change()
        $('#job_type_id').val('').change()
        $('#created_at_from').val('')
        $('#created_at_to').val('')
        $('#datatable').DataTable().draw(true);
    });


    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
    });

    $(document).on('click','.approve-reject', function() {
        var $this = $(this);
        var id = $this.attr('data-id');
        var status = $this.attr('data-active');
        var startupTable_row = $(this).closest("tr");
        var dialog_title = (status == 1 ? "Are you sure you want to approve this startup?" : "Are you sure you want to reject this startup?");

        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: approve_reject_link,
                    type: 'POST',
                    data: { id : id, status : status},
                    beforeSend: function(){
                        $('body').block({
                            message: '<i class="icon-spinner4 spinner"></i><br>Please Wait...',
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
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                // if(status == 2){
                                //     $this.removeClass('text-danger');
                                //     $this.addClass('text-success');
                                // }else{
                                //     $this.removeClass('text-success');
                                //     $this.addClass('text-danger');
                                // }
                                // startupTable.row(startupTable_row).remove().draw(false);
                                $this.parent('span').hide();
                                
                                if(status == 1){
                                    var action_link = "<span class='badge badge-success'><a href='javascript:;'>APPROVED</a></span>";
                                    console.log(action_link,'action_link')
                                    console.log($this.parents('td').find('.after_approve_reject'),'find')

                                    $this.parents('td').find('.after_approve_reject').html(action_link);
                                }

                                if(status == 2){
                                    var action_link = "<span class='badge badge-danger'><a href='javascript:;'>REJECTED</a></span>";
                                    $this.parents('td').find('.after_approve_reject').html(action_link);
                                }
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    complete: function(){
                        $('body').unblock();
                    }
                });
            }
        });
    });
    
});
   </script>
@endsection
