@extends('admin.app-admin')
@section('title') Appointments @endsection
@section('page-header')
<!-- Page header -->
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('job-title.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Appointments</a>
                <span class="breadcrumb-item active">Appointment Listing</span>
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
                    <label class="text-secondary">Start Date</label>
                    <input type="text" name="created_at_from" placeholder="Start Date" class="form-control" id="created_at_from">
                </div>
            </div>
            <div class="col-2">
                <div class="controls">
                    <label class="text-secondary">End Date</label>
                    <input type="text" name="created_at_to" placeholder="End Date" class="form-control" id="created_at_to">
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
                <th>Name</th>
                <th>Email</th>
                <th>Appointment Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $("#created_at_from").datetimepicker({
            ignoreReadonly: true,
            format: 'YYYY/MM/DD',
        }).data('autoclose', true);

        $("#created_at_to").datetimepicker({
            ignoreReadonly: true,
            format: 'YYYY/MM/DD',
        }).data('autoclose', true);;

        $("#created_at_from").on("dp.change", function (e) {
            $('#created_at_to').data("DateTimePicker").minDate(e.date);
        });
        $("#created_at_to").on("dp.change", function (e) {
            $('#created_at_from').data("DateTimePicker").maxDate(e.date);
        });
    });        

    var appointmentTable = "";
    var approve_reject_link = "{{ route('admin.appointment.approve-reject') }}";

    var filter = "{{ route('admin.appointment-filter') }}";

$(document).ready( function () {
    appointmentTable = $('#datatable').DataTable({
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
                d.job_title_id = $('#job_title_id').val();
                d.job_type_id = $('#job_type_id').val();
                d.created_at_from = $('#created_at_from').val();
                d.created_at_to = $('#created_at_to').val();
            },
            complete: function(){
                $('body').unblock();
            },
        },
        columns: [
            { data: 'name', name: 'name' ,searchable:false, orderable:false},
            { data: 'email', name: 'email' ,searchable:false, orderable:false},
            { data: 'appointment_date', name: 'appointment_date' ,searchable:false, orderable:false},
            { data: 'time', name: 'time' ,searchable:false},
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
        var appointmentTable_row = $(this).closest("tr");
        var dialog_title = (status == 1 ? "Are you sure you want to approve this appointment?" : "Are you sure you want to reject this appointment?");

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
                                if(status == 2){
                                    $this.removeClass('text-danger');
                                    $this.addClass('text-success');
                                }else{
                                    $this.removeClass('text-success');
                                    $this.addClass('text-danger');
                                }
                                appointmentTable.row(appointmentTable_row).remove().draw(false);
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