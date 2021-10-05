@extends('layouts.app')
@section('content')
<div class="page-main">
  <div class="user-profile-wraper">
    <div class="container">
        <div class="user-title-wrap">
            <h3 class="title">{{ __('Bank Account Details') }}</h3>
            <hr />
        </div>
        <div class="profile-top-detial">
          @if ($stripeDetails)
            @if ($stripeDetails->details_submitted == 'true')                
              <div>
                <strong>Bank Name :</strong> <span>{{ $stripeDetails->bank_name ?? '-' }}</span>
              </div>
              <div>
                <strong>Account Number :</strong> <span>********{{ $stripeDetails->last4 ?? '-' }}</span>
              </div>
              <div>
                <strong>Routing Number :</strong> <span>{{ $stripeDetails->routing_number ?? '-' }}</span>
              </div>
              <div>
                <strong>Account Setup :</strong> <span>{{ $stripeDetails->account_status ?? '-' }}</span>
              </div>
            @else
              <a href="{{ route('create-account') }}" class="btn btn-primary">Completed Bank Details</a>              
            @endif
          @else
              <a href="{{ route('create-account') }}" class="btn btn-primary">Add Bank Details</a>
          @endif
        </div>
    </div>
  </div>
</div>
@endsection