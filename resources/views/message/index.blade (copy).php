@extends('layouts.app')
@section('content')
<style>
    .message-box {
        height: 500px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<div class="main_page" id="property-page">
    <!-- main content -->
    <div class="page-content">
        <div class="content-wrapper">
            <div class="chat-section section-spacer">
                <div class="row ">
                    <div class="col-md-4 col-lg-4 pt-3 pl-5">
                        <div class="member-item p-2 border d-flex justify-content-between">
                            <form action="/search-member" method="post" class="input-group">
                                <input type="text" name="keyword" id="keyword" placeholder="Search member" class="form-control mb-2" required title="Please enter member name" oninvalid="this.setCustomValidity('Please enter member name')" oninput="this.setCustomValidity('')" >
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                                    </div>
                            </form>
                        </div>
                        @include('message.chats-list')
                    </div>
                    <div class="col-md-8 col-lg-8">
                        <div class="section-header-header my-3">
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('page.members') }}" class="btn btn-secondary font-medium"></i><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div> --}}
                        </div>
                        <div class="border-1 radius-normal my-3">
                            <div class="chat-header border-bottom-1 p-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="normal-content d-flex align-items-center">
                                            <div class="left-content">
                                                <p class="font-light m-0">Name : <strong class="text-black font-regular">{{ $user->name ?? "" }}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="chat-block-content">
                                    <div class="message-box p-3" id="chat_box">
                                        @include('message.ajax.message-list')
                                    </div>
                                </div>
                                <div class="chat-footer px-3 py-2 border-top-1 custom-forms">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <form id="f-send-message" method="POST" action="{{ route('member.send-message') }}"
                                                enctype="multipart/form-data" autocomplete="off">
                                                @method('POST')
                                                @csrf
                                                <input type="hidden" name="group_id" value="{{ $new_group_id ?? "" }}">
                                                <input type="hidden" name="receiver_id" value="{{ $user->id ?? "" }}">
                                                <div class="form-group m-0">
                                                    <textarea class="form-control text-black border-0 " rows="2"
                                                        name="type_msg" placeholder="Please type message"
                                                        required="">{{ old('type_msg') }}</textarea>
                                                </div>
                                                <ul class="fileList pl-1 m-0"></ul>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary submit-btn">
                                                    <i class="fa fa-paper-plane mr-2 submit-icon"></i>
                                                    Send
                                                </button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main content -->
</div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/pages/message.js') }}"></script>
<script>
    var get_ajax_message_list = "{{ route('get_message_list') }}";
    var page = 1;
    document.getElementById("chat_box").scrollBy(0, 5000);

    $(document).ready(function(){

    $('#chat_box').on("scroll", function() {
    console.log('scroll');
    var scrollHeight = $("#chat_box").height();
    var scrollPosition = $("#chat_box").height() + $("#chat_box").scrollTop();
    console.log('scrollHeight',scrollHeight);
    console.log('scrollPosition',scrollPosition);
	if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
	    // when scroll to bottom of the page
        // console.log('at top');   
        page++;     
        loadMoreData(page);
        }
    });


    $(document).ready(function(){   
	    if($("#chat_box").scrollTop() + $("#chat_box").height() >= $("#chat_box").prop('scrollHeight')) {
	        page++;
            // console.log('in');
	        loadMoreData(page);
	    }
	    });
	});

	function loadMoreData(page){
	  $.ajax(
	        {
	            url: '?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {                    
                    // blockContentBody(); 
	            }
	        })
	        .done(function(data)
	        {                
	            if(data.html == ""){
                //   $(".no-record").show();
                //   unBlockContentBody();
	              return;
	            }else{
                // unBlockContentBody();
	            $("#chat_box").prepend(data.html);
                document.getElementById("chat_box").scrollBy(0, 500);
                }
	        })
	        .fail(function(jqXHR, ajaxOptions, thrownError)
	        {
                console.log(jqXHR);
                console.log(ajaxOptions);
                console.log(thrownError);                
            // unBlockContentBody();
	        });
	}
</script>
@endsection