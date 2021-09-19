@extends('admin.app-admin')
@section('title') Fund Request Details @endsection
@section('content')
@php
    $statuss = config('constant.appointment_status');
    $share_url = Request::url();
@endphp
<h6 class="card-title text-center">Fund Request Details</h6>
<div class="card">
    <div class="card-body custom-tabs">
        <div class="row">
            <div class="col-md-10">
                <div class="detail-section">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Title</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->title }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal"><span class='badge badge-success'><a href='javascript:;'>
                            @php
                                if($fund->status == '0'){
                                    echo "PENDING";
                                } else if($fund->status == '1'){
                                    echo "APPROVED";
                                } else {
                                    echo "REJECTED";
                                }
                            @endphp    
                            </a></span></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Currency</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->currency }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Amount</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->amount }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Received Amount</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->received_amount ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Donors</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal">{{ $fund->donors }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="font-weight-bold label-before">Description</label>
                        </div>
                        <div class="col-lg-8">
                            <p class="font-normal"></p>{!! $fund->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4>Donors List</h4>
        <div class="row">
            <div class="col-2">
                <div class="controls">
                    <label class="text-secondary">Email</label>
                    <input type="text" name="keyword" placeholder="Email" class="form-control" id="keyword">
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
                <th>Payment Id</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Payment Status</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    var filter = "{{ route('admin.fund-donor-list') }}";

    $(document).ready( function () {
    fundRequest = $('#datatable').DataTable({
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
            { data: 'payment_id', name: 'payment_id' ,searchable:false, orderable:false},
            { data: 'email', name: 'email' ,searchable:false, orderable:false},
            { data: 'amount', name: 'amount' ,searchable:false, orderable:false},
            { data: 'payment_status', name: 'payment_status', searchable:false, orderable:false }
        ]
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#keyword').val('').change();
        $('#datatable').DataTable().draw(true);
    });
     
});
</script>
@endsection
