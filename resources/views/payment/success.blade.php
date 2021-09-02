@extends('layouts.app')

@section('content')
@php
    $accId = Session::get('stripe_acc_id');
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h3>Paynow</h3>
            </div>
            <div class="content">
                <form action="/payment" method="POST" class="form-group">
                    @csrf
                    <input type="text" name="accId" id="accId" class="form-control" placeholder="Account Id" value="{{ $accId ?? "" }}">
                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                    <button type="submit" class="btn btn-primary">
                        Pay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
