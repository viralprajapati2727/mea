@extends('admin.app-admin')
@section('title') Active Jobs @endsection
@section('page-header')
<!-- Page header -->
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('job-title.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Active Jobs</a>
                <span class="breadcrumb-item active">Active Job Listing</span>
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
            <div class="col-3">
                <div class="controls">
                    <label class="text-secondary">Job Title</label>
                    <select class="form-control form-control-select2" name="job_title_id" id="job_title_id">
                        <option value="">All</option>
                        @forelse ($jobtitles as $jobtitle)
                        <option value="{{ $jobtitle->id }}"> {{ $jobtitle->title }} </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="controls">
                    <label class="text-secondary">Job Type</label>
                    <select class="form-control form-control-select2" name="job_type_id" id="job_type_id">
                        <option value="">All</option>
                        @forelse (config('constant.job_type') as $key => $job_type)
                        <option value="{{ $key }}"> {{ $job_type }} </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
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
                <th>Job Title</th>
                <th>Type Of Job</th>
                <th>Job Type</th>
                <th>Salary Range</th>
                <th>Start & End Time</th>
                <th>Posted Date</th>
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

    var activeJobTable = "";
    var delete_link = "{{ route('admin.job.destroy') }}";

    var filter = "{{ route('admin.active-job-filter') }}";

$(document).ready( function () {
    activeJobTable = $('#datatable').DataTable({
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
            { data: 'jobtitle', name: 'jobtitle' ,searchable:false, orderable:false},
            { data: 'job_type', name: 'job_type' ,searchable:false, orderable:false},
            { data: 'jobtype', name: 'jobtype' ,searchable:false, orderable:false},
            { data: 'salary_range', name: 'salary_range' ,searchable:false},
            { data: 'job_time', name: 'job_time' ,searchable:false},
            { data: 'created_at', name: 'created_at' ,searchable:false},
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

    $(document).on('click','.deleted', function() {
        var id= $(this).data('id');
        var activeJobTable_row = $(this).closest("tr");
        swal({
            title: 'Are you sure you want to delete this job?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: delete_link,
                    type: 'POST',
                    data: { id : id, },
                    beforeSend: function () {
                        $("body").block({
                            message: '<i class="icon-spinner4 spinner"></i>',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.8,
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
                                activeJobTable.row(activeJobTable_row).remove().draw(false);
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
                    complete: function () {
                        $("body").unblock();
                    }
                });
            }
        }, function (dismiss) {
        });
    });
});
   </script>
@endsection
