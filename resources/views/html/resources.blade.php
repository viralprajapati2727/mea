@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="resources-main">
        <div class="container">
            <div class="title text-center">
                <h1>Resources</h1>
            </div>
            <div class="resources-lists">
                <div class="resources-wrap">
                    <div class="row">
                        <div class="col-md-6 rs-content">
                            <h2 class="rs-title">Business Plan</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever 
                            since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
                            <a href="#" class="rs-link">Read more..</a>
                        </div>
                        <div class="col-md-6 rs-image">
                            <div class="rs-image-wrap">
                                <img class="w-100" src="{{ Helper::assets('images/resources/business-plan.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="resources-wrap even">
                    <div class="row">
                        <div class="col-md-6 rs-content">
                            <h2 class="rs-title">Market Research</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever 
                            since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
                            <a href="#" class="rs-link">Read more..</a>
                        </div>
                        <div class="col-md-6 rs-image">
                            <div class="rs-image-wrap">
                                <img class="w-100" src="{{ Helper::assets('images/resources/market-research.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="resources-wrap">
                    <div class="row">
                        <div class="col-md-6 rs-content">
                            <h2 class="rs-title">Financial Planning</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever 
                            since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
                            <a href="#" class="rs-link">Read more..</a>
                        </div>
                        <div class="col-md-6 rs-image">
                            <div class="rs-image-wrap">
                                <img class="w-100" src="{{ Helper::assets('images/resources/financial-planning.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="resources-wrap even">
                    <div class="row">
                        <div class="col-md-6 rs-content">
                            <h2 class="rs-title">Pitch Deck</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever 
                            since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
                            <a href="#" class="rs-link">Read more..</a>
                        </div>
                        <div class="col-md-6 rs-image">
                            <div class="rs-image-wrap">
                                <img class="w-100" src="{{ Helper::assets('images/resources/pitch-deck.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection