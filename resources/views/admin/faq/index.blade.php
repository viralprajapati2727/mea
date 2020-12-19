@extends('admin.app-admin')
@section('title') FAQ @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('faq.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>FAQ</a>
                <span class="breadcrumb-item active">FAQ Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>FAQ</h5>
        <a href="{{ route('faq.create') }}" class="btn btn-primary rounded-round"><i class="icon-plus2"></i> Add New FAQ</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <div class="event-search-icon">
                        <i class="flaticon-search"></i>
                    </div>
                    <label class="text-secondary">Question</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-3 mt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state" id="datatable">
        <thead>
            <tr>
                <th>Question</th>
                <th>Date Updated</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@section('footer_content')
<script>
    var active_link = "{{ route('faq-change-status') }}";
    var faq_filter = "{{ route('faq-filter') }}";

    var faqTable = "";
    $(document).ready( function () {
            faqTable = $('#datatable').DataTable({
            serverSide: true,
            bFilter:false,
            'bSort': true,   
            ajax: {
                url: faq_filter,
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
                { data: 'question', name: 'question' ,searchable:false},
                { data: 'date_updated', name: 'date_updated' ,searchable:false},
                { data: 'status', name: 'status' ,searchable:false},
                { data: 'action', name: 'action', searchable:false}
            ]
        });
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#faq_category').val('').change();
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });
    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
    });

    $(document).on('click','.faq-deleted', function() {
        var id= $(this).data('id');
        var faqTable_row = $(this).closest("tr");
        swal({
            title: 'Are you sure you want to delete this question?',
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
                    url: 'faq/'+id,
                    type: 'DELETE',
                    data: { id : id, },
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
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                faqTable.row(faqTable_row).remove().draw(false);
                            });
                        }else if(response.status == 201){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#FF7043",
                                confirmButtonClass: 'btn btn-warning',
                                type: "warning",
                                confirmButtonText: 'OK',
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
        }, function (dismiss) {
        });
    });

    $(document).on('click', '.faq-status', function() {
        var $this = $(this);
        var id = $this.attr('data-id');
        var status = $this.attr('data-active');
        var deactive = (status == 1 ? 0 : 1);
        
        var active_label = (status == 1 ? "ACTIVE" : "INACTIVE");
        var dialog_title = (status == 1 ? "Are you sure you want to active this question?" : "Are you sure you want to Inactive this question?");

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
                    url: active_link,
                    type: 'POST',
                    data: { id : id, status : status},
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                if(deactive == 0){
                                    $this.parent('span').removeClass('badge-danger');
                                    $this.parent('span').addClass('badge-success');
                                }else{
                                    $this.parent('span').removeClass('badge-success');
                                    $this.parent('span').addClass('badge-danger');
                                }
                                var label = $this.html(active_label);
                                
                                $this.attr('data-active',deactive);
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
                });
            }
        });
    })
   </script>
@endsection
