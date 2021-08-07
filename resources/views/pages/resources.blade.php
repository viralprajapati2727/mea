@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="resources-main">
        <div class="container">
            <div class="resources-top-wraper">
                <h2 class="title">Free Startup Plan, Budget & Cost Templates</h2>
                <div class="text">
                    <p>A business plan describes how a new business will meet its primary objectives over a given period of time. It is both a strategic document that can act as a roadmap and a tool for securing funding and communicating with stakeholders. For a startup business, planning is key to developing a thorough understanding of the target market, competition, market conditions, and financing opportunities.</p>
                    <p>Included on this page, you'll find a variety of helpful, free startup business planning templates, like a SWOT analysis template, a competitive analysis template, a business startup checklist template, and more.</p>
                </div>
            </div>
            <div class="resources-wraper">
                @if(!empty($resources) && $resources->count())
                    <div class="resource-category">
                        <h2 class="cat-title">{{ $resources[0]->topic->title ? $resources[0]->topic->title :  "" }}'s Resources</h2>
                    </div>
                    <div class="resources-right-wraper">
                        @foreach ($resources as $key => $resource)
                        @php
                            $srcUrl = Helper::images(config('constant.resource_url'));
                            $srcUrl = $srcUrl . $resource->src;
                            $documentUrl = null;
                            if ($resource->document) {
                                $documentUrl = Helper::images(config('constant.resource_document_url'));
                                $documentUrl = $documentUrl . $resource->document;
                            }
                        @endphp

                        <div class="resorces-article">
                            <div class="article-inner">
                                <div class="heading">
                                    <h2 class="atricle-title" id="s{{ $resource->topic_id }}">{{ $resource->title ?? "" }}</h2>
                                </div>
                                <div class="content">
                                    <div class="text">
                                        <img src={{ $srcUrl }} height="350" alt="resource image" class="rounded" />
                                        <p class="text-justified">{!! $resource->description ?? "" !!}</p>
                                        @if ($resource->document)
                                            <p class="text-center">
                                                {{-- <a href="{{ $srcUrl }}" download>Image</a> @if($documentUrl != null) |  --}}
                                                <a href="{{ $documentUrl }}" download class="download-link">
                                                    <img src="{{ Helper::assets('images/resources/down-arrow.png') }}">
                                                    Download Resource Document
                                                </a>
                                                {{-- @endif --}}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <h2>No Resources found!</h2>    
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer_script')
<script>
$(document).ready(function () { 
    var header_height = $('.site-header').outerHeight(); 
    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('.resources-sidebar').offset().top;
        if (window_top > div_top) {
            $('.resources-sidebar').addClass('stick'); 
        } else {
            $('.resources-sidebar').removeClass('stick');
        }
    }

    $(function() {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
    
    $( '.resources-selection-list a' ).on( 'click', function(e){
        var href = $(this).attr( 'href' );
        $( 'html, body' ).animate({
            scrollTop: $( href ).offset().top - header_height
        }, '300' );
        
        e.preventDefault();
    
    });

    $(".resources-selection-list > li >a").click(function (e) {
        e.preventDefault();
        $(this).toggleClass('open');
        $(this).closest(".list-item").find(".second-level-selection").stop().slideToggle();
    });

});

</script>
@endsection