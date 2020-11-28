@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="user_profile_form_page fill-profile">
            <div class="d-md-flex">
                <div class="col-md-12 p-0">
                    <p class="h5">Fill your profile detail and help us personalize your platform experience!</p>
                </div>
            </div>
            <form class="entrepreneur_profile_form " action="{{ route('entrepreneur.store-profile') }}" data-fouc method="POST" enctype="multipart/form-data" autocomplete="off">
                @method('POST')
                @csrf
                <div class="row mt-md-0 mt-3 pb-0">
                    <div class="col-lg-9 col-md-12">
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer_script')
@endsection
