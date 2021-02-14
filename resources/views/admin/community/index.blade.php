@extends('admin.app-admin')
@section('title') Questions @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.question.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Questions</a>
                <span class="breadcrumb-item active">Question Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Questions</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <label class="text-secondary">Question</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-3 padding-top-class pt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state dataTable no-footer" role="grid" id="datatable">
        <thead>
            <tr>
                <th>Question</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@section('footer_content')
{{-- <script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script> --}}
<script>

    var jobTitleTable = "";
    
    var filter = "{{ route('question-filter') }}";
    var flag = 1;

$(document).ready( function () {
    jobTitleTable = $('#datatable').DataTable({
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
            { data: 'title', name: 'title' ,searchable:false, orderable:false},
            { data: 'status', name: 'status' ,searchable:false},
            { data: 'action', name: 'action', searchable:false, orderable:false }
        ]
    });


    // $(document).on('click','.openCurrencyPopoup',function() {
    //     var id = $(this).data('id');
    //     var title = $(this).data('title');
    //     var code = $(this).data('code');
    //     var symbol = $(this).data('symbol');
    //     $('.add_modal').modal({backdrop: 'static', keyboard: false});
    //     $('.add_modal .currency_id').val(id);
    //     var currencyId = $('.currency_id').val();
    //     if(currencyId){
    //         $('.add_modal .title').val(title);
    //         $('.add_modal .code').val(code);
    //         $('.add_modal .symbol').val(symbol);
    //     }
    // })

    // $(document).on('click','.custom_close',function(){
    //     setTimeout(function(){
    //         $('.validation-invalid-label').remove();
    //         $("#currency_form").trigger("reset");
    //         $('.currency_id').val(null);
    //         $('.add_modal .title').val('');
    //         $('.add_modal .code').val('');
    //         $('.add_modal .symbol').val('');
    //         $('.add_modal .old_src').val('');
    //     }, 700);
    // });

    // $(document).on('click','.add_currency',function(){
    //     $('.job_title').text('Add Currency');
    //     $('.job_label').html('Add Currency: <span class="text-danger">*</span>');
    // })

    // $(document).on('click','.edit_currency',function(){
    //     var $this = $(this);
    //     $('.job_title').text('Edit Currency');
    //     $('.job_label').html('Edit Currency: <span class="text-danger">*</span>');
    //     if($this.closest('tr').find('.type-status').attr('data-active') == 1){
    //         $('#custom_checkbox_stacked_unchecked').removeAttr('checked');
    //     }else {
    //         $('#custom_checkbox_stacked_unchecked').attr('checked', true);
    //     }
    // });

    // $(document).on('click','.type-status',function () {
    //     var $this = $(this);
    //     var id = $this.attr('data-id');
    //     var active = $this.attr('data-active');
    //     var deactive = (active == 1 ? 0 : 1);
    //     var active_label = (active == 1 ? "ACTIVE" : "INACTIVE");
    //     var dialog_title = (active == 1 ? "Are you sure you want to active Currency?" : "Are you sure you want to deactive Currency?");

    //     swal({
    //         title: dialog_title,
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'No',
    //         confirmButtonClass: 'btn btn-success',
    //         cancelButtonClass: 'btn btn-danger',
    //         buttonsStyling: false
    //     }).then(function (confirm) {
    //         if(confirm.value !== "undefined" && confirm.value){
    //             $.ajax({
    //                 url: active_link,
    //                 type: 'POST',
    //                 data: { id : id, active : active},
    //                 success: function(response) {
    //                     if(response.status == 200){
    //                         swal({
    //                             title: response.msg_success,
    //                             confirmButtonColor: "#66BB6A",
    //                             type: "success",
    //                             confirmButtonText: 'OK',
    //                             confirmButtonClass: 'btn btn-success',
    //                         }).then(function (){
    //                             if(deactive == 0){
    //                                 $this.parent('span').removeClass('badge-danger');
    //                                 $this.parent('span').addClass('badge-success');
    //                             }else{
    //                                 $this.parent('span').addClass('badge-danger');
    //                                 $this.parent('span').removeClass('badge-success');
    //                             }
    //                             $this.html(active_label);
    //                             $this.attr('data-active',deactive);
    //                         });
    //                     }else{
    //                         swal({
    //                             title: response.msg_fail,
    //                             confirmButtonColor: "#EF5350",
    //                             confirmButtonClass: 'btn btn-danger',
    //                             type: "error",
    //                             confirmButtonText: 'OK',
    //                         });
    //                     }
    //                 },
    //             });
    //         }
    //     });
    // });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });


    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
    });



    // $(document).on('click','.jobtitle_deleted', function() {
    //     var id= $(this).data('id');
    //     var deleted= $(this).data('inuse');
        
    //     var dialog_title = 'Are you sure want to delete this Currency ?';
    //     if(deleted != ''){
    //         dialog_title = 'This Currency is already in use, Are you sure want to delete this Currency?'
    //     }
    //     var jobTitleTable_row = $(this).closest("tr");
    //     swal({
    //         title: dialog_title,
    //         type: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'No',
    //         confirmButtonClass: 'btn btn-success',
    //         cancelButtonClass: 'btn btn-danger',
    //         buttonsStyling: false
    //     }).then(function (confirm) {
    //         if(confirm.value !== "undefined" && confirm.value){
    //             $.ajax({
    //                 url: 'currency/'+id,
    //                 type: 'DELETE',
    //                 data: { id : id, },
    //                 beforeSend: function(){
    //                     $('body').block({
    //                         message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
    //                         overlayCSS: {
    //                             backgroundColor: '#000',
    //                             opacity: 0.15,
    //                             cursor: 'wait'
    //                         },
    //                         css: {
    //                             border: 0,
    //                             padding: 0,
    //                             backgroundColor: 'transparent'
    //                         }
    //                     });
    //                 },
    //                 success: function(response) {
    //                     if(response.status == 200){
    //                         swal({
    //                             title: response.msg_success,
    //                             confirmButtonColor: "#66BB6A",
    //                             type: "success",
    //                             confirmButtonText: 'OK',
    //                             confirmButtonClass: 'btn btn-success',
    //                         }).then(function (){
    //                             jobTitleTable.row(jobTitleTable_row).remove().draw(false);
    //                         });
    //                     } else if(response.status == 201){
    //                         swal({
    //                             title: response.msg_success,
    //                             confirmButtonColor: "#FF7043",
    //                             confirmButtonClass: 'btn btn-warning',
    //                             type: "warning",
    //                             confirmButtonText: 'OK',
    //                         });
    //                     }else{
    //                         swal({
    //                             title: response.msg_fail,
    //                             confirmButtonColor: "#EF5350",
    //                             confirmButtonClass: 'btn btn-danger',
    //                             type: "error",
    //                             confirmButtonText: 'OK',
    //                         });
    //                     }
    //                 },
    //                 complete: function(){
    //                     $('body').unblock();
    //                 }
    //             });
    //         }
    //     }, function (dismiss) {
    //     });
    // });

    // Modal template
    // var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
    //     '  <div class="modal-content">\n' +
    //     '    <div class="modal-header align-items-center">\n' +
    //     '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
    //     '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
    //     '    </div>\n' +
    //     '    <div class="modal-body">\n' +
    //     '      <div class="floating-buttons btn-group"></div>\n' +
    //     '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
    //     '    </div>\n' +
    //     '  </div>\n' +
    //     '</div>\n';

    // Buttons inside zoom modal
    // var previewZoomButtonClasses = {
    //     toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
    //     fullscreen: 'btn btn-light btn-icon btn-sm',
    //     borderless: 'btn btn-light btn-icon btn-sm',
    //     close: 'btn btn-light btn-icon btn-sm'
    // };

    // Icons inside zoom modal classes
    // var previewZoomButtonIcons = {
    //     prev: '<i class="icon-arrow-left32"></i>',
    //     next: '<i class="icon-arrow-right32"></i>',
    //     toggleheader: '<i class="icon-menu-open"></i>',
    //     fullscreen: '<i class="icon-screen-full"></i>',
    //     borderless: '<i class="icon-alignment-unalign"></i>',
    //     close: '<i class="icon-cross2 font-size-base"></i>'
    // };

    // File actions
    // var fileActionSettings = {
    //     zoomClass: '',
    //     zoomIcon: '<i class="icon-zoomin3"></i>',
    //     dragClass: 'p-2',
    //     dragIcon: '<i class="icon-three-bars"></i>',
    //     removeClass: '',
    //     removeErrorClass: 'text-danger',
    //     removeIcon: '<i class="icon-bin"></i>',
    //     indicatorNew: '<i class="icon-file-plus text-success"></i>',
    //     indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
    //     indicatorError: '<i class="icon-cross2 text-danger"></i>',
    //     indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    // };

    // Basic example

    // $('.file-input').fileinput({
    //     browseLabel: 'Browse',
    //     browseIcon: '<i class="icon-file-plus mr-2"></i>',
    //     uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
    //     removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
    //     layoutTemplates: {
    //         icon: '<i class="icon-file-check"></i>',
    //         modal: modalTemplate
    //     },
    //     initialCaption: "No file selected",
    //     previewZoomButtonClasses: previewZoomButtonClasses,
    //     previewZoomButtonIcons: previewZoomButtonIcons,
    //     fileActionSettings: fileActionSettings
    // });

});
   </script>
@endsection
