@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner faq">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1>FAQ</h1>
                    <p>Find the frequently asked questions here.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
        <div class="container">
            <div class="faq-wraper" id="solid-justified-tab1">
                <div class="card-group-control card-group-control-right">
                    @forelse ($faqs as $key => $faq)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title myClass">
                                    <a class="collapsed text-default" data-toggle="collapse" href="#general_{{ $key }}">
                                        <span class="count">{{ $key + 1 }}.</span>
                                        {{ $faq->question }}
                                    </a>
                                </h6>
                            </div>
                            <div id="general_{{ $key }}" class="collapse">
                                <div class="card-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No data found!!</p>
                    @endforelse
                </div>
            </div>                                                                                             
        </div>
    </div>
</div>
@endsection 