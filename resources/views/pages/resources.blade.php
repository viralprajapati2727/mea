@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="resources-main">
        <div class="container">
            <div class="title text-center">
                <h1>Resources</h1>
            </div>
            <div class="resources-lists">
                @forelse ($resources as $key => $resource)
                    @php
                        $resourceurl = Helper::images(config('constant.resource_url'));
                        $img_url = $resourceurl.$resource->src;
                        $class = "";
                        if($key % 2 == 0){
                            $class = "even";
                        }
                    @endphp
                    <div class="resources-wrap {{ $class }}">
                        <div class="row">
                            <div class="col-md-6 rs-content">
                                <h2 class="rs-title">{{ $resource->title }}</h2>
                                <p>{{ $resource->short_description }}</p>
                                <a href="{{ route('page.resource-detail',['slug' => $resource->slug]) }}" class="rs-link">Read more..</a>
                            </div>
                            <div class="col-md-6 rs-image">
                                <div class="rs-image-wrap">
                                    <img class="w-100" src="{{ $img_url }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection