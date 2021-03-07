@extends('layouts.app')
@section('content')
@php

@endphp
<div class="container">
    <div class="user_profile_form_page fill-profile">
        <div class="d-md-flex justify-content-center">
            <div class="col-md-9 p-0">
                <h2>Raise Fund Request</h2>
            </div>
        </div>
        <div class="raise-fund">
            <form class="raise-fund-form " action="{{ route('startup-raise-fund.store') }}" data-fouc method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @method('POST')
                @csrf
                <input type="hidden" name="id" value="{{ $fund['id'] ?? "" }}">
                <input type="hidden" name="status" value="{{ $fund['status'] ?? "0" }}">
                <div class="row mt-md-0 mt-3 pb-0 justify-content-center">
                    <div class="col-lg-9 col-md-12">
                        <div class="row mt-md-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Title <span
                                            class="required-star-color">*</span></label>
                                    <input type="text" class="form-control " name="title" id="title" placeholder="Title"
                                        value="{{ $fund['title'] ?? old('title') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-control-label">Currency </label>
                                    <select name="currency" id="currency" class="form-control select2 no-search-select2" data-placeholder="Select Currency">
                                        <option></option>
                                        @forelse (config('constant.fund_currency') as $currency)
                                            <option value="{{ $currency }}" 
                                                {{ ($fund) ? ($fund->currency == $currency ? 'selected' : ''): ''  }}
                                                >{{ $currency }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Amount <span
                                            class="required-star-color">*</span></label>
                                    <input type="number" class="form-control " name="amount" id="amount" placeholder="Amount"
                                        value="{{ $fund['amount'] ?? old('amount') }}" min="100">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-md-2">
                            <div class="col-12">
                                <div class="form-group ckeditor">
                                    <label class="form-control-label">Description <span
                                            class="required-star-color">*</span></label>
                                    <textarea name="description" id="description" rows="5"
                                        class="form-control description"
                                        placeholder="Description">{{  $fund['description'] ?? old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 btn-section d-md-flex d-lg-flex align-items-center position-relative pb-2 text-center text-md-left justify-content-end">
                            <button type="submit" class="btn custom-btn member-login-btn justify-content-center text-white px-5 rounded-lg submit-btn"><i class="flaticon-save-file-option mr-2 submit-icon"></i>Send Request</button>
                            <span class="pl-3 d-md-inline-block d-lg-inline-block pt-4 pt-md-0 pt-lg-0 text-center"><a href="{{route('startup.raise-fund')}}" class="text-common-color font-semi-bold entry-cancel">CANCEL</a></span>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
    var is_form_edit = false;
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/raise_fund.js') }}"></script>
@endsection