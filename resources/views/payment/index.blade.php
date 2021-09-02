@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Stripe Payment') }}</div>

                <div class="card-body">
                    <div class="content">
                        <form action="/create-account" method="post">
                            @csrf
                            <button type="submit">
                                Start
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
