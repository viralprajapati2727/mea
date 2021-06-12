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
                <div class="resources-sidebar">
                    <div class="resources-navigation">
                        <ul class="resources-selection-list">
                            @foreach ($topics as $key => $topic)
                            @php
                                $subTopics = Helper::getSubTopics($topic->id);
                            @endphp
                            <li class="list-item text-capitalize">
                                <a href="#s{{ $topic->id }}"
                                    @if (sizeof($subTopics) > 0)
                                    class="has-subitem"
                                    @endif
                                    >{{ $topic->title ?? "" }}</a>
                                    @if (sizeof($subTopics) > 0)
                                    @foreach ($subTopics as $sTopic)
                                            <ul class="second-level-selection">
                                                <li>
                                                    <a href="#s{{ $sTopic->id }}">{{ $sTopic->title  ?? '-' }}</a>
                                                </li>
                                            </ul>
                                        @endforeach
                                    @endif
                            </li>
                            {{-- <li class="list-item">
                                <a href="#why-write-a-startup-business-plan"> Why Write a Startup Business Plan?</a>
                            </li>
                            <li class="list-item">
                                <a href="#what-to-include-in-a-business-plan">What to Include in a Business Plan</a>
                            </li> --}}
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="resources-right-wraper">
                    @foreach ($resourcesNew as $key => $resource)
                    <div class="resorces-article">
                        <div class="article-inner">
                            <div class="heading">
                                <h2 class="atricle-title" id="s{{ $resource->topic_id }}">{{ $resource->title ?? "" }}</h2>
                            </div>
                            <div class="content">
                                {{-- <h3 class="subtitle" id="competitive-analysis-template-excel">Competitive Analysis Template - Excel</h3> --}}
                                <div class="text">
                                    <p>{!! $resource->description ?? "" !!}</p>
                                    {{-- <p>Analyze multiple competitors based on the categories you want to compare, and use the results to identify your top rivals. This template contains several sheets to provide a comprehensive look at how your startup stacks up to the competition, the strengths of each company, and potential partnerships or opportunities.</p> --}}
                                    @if ($resource->src !== "")
                                    @php
                                        $srcUrl = Helper::images(config('constant.resource_url'));
                                        $srcUrl = $srcUrl . $resource->src;
                                        $documentUrl = null;
                                        if ($resource->document) {
                                            $documentUrl = Helper::images(config('constant.resource_document_url'));
                                            $documentUrl = $documentUrl . $resource->document;
                                        }
                                    @endphp
                                        <p class="text-center">
                                            Download Resource Image/Document <br>
                                            <a href="{{ $srcUrl }}" download>Image</a> @if($documentUrl != null) | <a href="{{ $documentUrl }}" download>Document</a> @endif
                                        </p>
                                    @endif
                                </div>
                                @php
                                    $subResource = Helper::getSubTopicsResource($resource->topic_id)
                                @endphp
                                @if (sizeof($subResource) > 0)
                                    @foreach ($subResource as $item)
                                        <h3 class="subtitle" id="s{{ $item->topic_id }}">{{ $item->title ?? "-" }}</h3>
                                        <div class="text">
                                            <p>{!! $item->description ?? "" !!}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
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