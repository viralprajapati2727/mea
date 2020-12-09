@extends('admin.app-admin')
@section('title') Dancer Management @endsection
@section('page-header')
    @php
        $profilePicOrgDynamicUrl = str_replace('{userSlug}', $details->slug, config('constant.profile_url'));
        $profilePicThumbDynamicUrl = str_replace('{userSlug}', $details->slug, config('constant.profile_thumb_url'));
        $galleryOrgDynamicUrl = str_replace('{userSlug}', $details->slug, config('constant.gallery_url'));
        $galleryThumbDynamicUrl = str_replace('{userSlug}', $details->slug, config('constant.gallery_thumb_url'));
        $defaultProUrl = Helper::images(config('constant.default_profile_url')).'default.png';
    @endphp
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('admin.professional.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Professional</a>
                    <span class="breadcrumb-item active">Professional Listing</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <h6 class="card-title text-center">Professional Details</h6>
    <div class="card">
        <div class="card-header">
            <div class="col-8 float-right text-right mr-4 action-buttons action-class">
                @if(request()->get('from') != 'report')
                    <button type="text" data-flag="0" data-status="{{ $details->is_active }}" data-id="{{ $details->id }}" data-suspend_date = "{{ $details->is_active == 4 ? $details->suspended_till : NULL }}" id="{{ $details->is_active == 4 ? "activate" : "suspend" }}" class="{{ $details->is_active == 4 || $details->is_active == 3 ? "btn btn-success activate_user" : "btn btn-danger suspend_user" }} rounded-round user-status">{{ $details->is_active == 4 || $details->is_active == 3 ? 'Activate' : 'Suspend' }}</button>
                    <a href="javascript:;" data-id="{{ $details->id }}" id="" class="btn btn-primary rounded-round text-white remove_user">Remove</a>
                @endif
                <input type="hidden" name="user_type" class="remove_user_type" value="{{ $details->type }}">
                @if(!empty($report)  && $report->status == 0)
                    <button type="text" data-id="{{ $report->id }}" data-status="1" data-type="{{ $report->type }}"  class="rounded-round report-status btn btn-success status-approve" {{ $report->status == 1 ? 'style = display:none' : '' }}>Approve Spam Request</button>
                    <button type="text" data-id="{{ $report->id }}" data-status="2" data-type="{{ $report->type }}"  class="rounded-round report-status btn btn-danger status-reject" {{ $report->status == 2 ? 'style = display:none' : '' }}>Reject Spam Request</button>
                @endif
            </div>
        </div>

        <div class="card-body custom-tabs">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded">
                <li class="nav-item"><a href="#solid-rounded-bordered-justified-tab1" class="nav-link rounded-left active show" data-toggle="tab">Profile</a></li>
                @if($is_wallet_acsess == 1)
                    <li class="nav-item"><a href="#solid-rounded-bordered-justified-tab2" class="nav-link" data-toggle="tab">Wallet</a></li>
                @endif
                <li class="nav-item booking-section"><a href="#solid-rounded-bordered-justified-tab3" class="nav-link" data-toggle="tab">Bookings</a></li>
                <li class="nav-item"><a href="#solid-rounded-bordered-justified-tab4" class="nav-link" data-toggle="tab">Events</a></li>
                <li class="nav-item"><a href="#solid-rounded-bordered-justified-tab5" class="nav-link" data-toggle="tab">Bank</a></li>
                <li class="nav-item"><a href="#solid-rounded-bordered-justified-tab6" class="nav-link" data-toggle="tab">Event Manager</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade active show" id="solid-rounded-bordered-justified-tab1">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="detail-section">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Name</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">{{ $details->name }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">NickName</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">{{ $details->nick_name }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="d-flex nickname-switch align-items-center">
                                        <div class="form-check-switchery mb-0 pl-2">
                                            <label class="setting-toggle-switch form-check-label">
                                                <input type="checkbox" name="is_nickname_use" class="is_nickname_use" value="1" data-id={{ $details->id }} data-nickname={{ $details->is_nickname_use }} {{  $details->is_nickname_use == 1 ? 'checked':''}}/>
                                                <div class="setting-toggle-switch-slider round"></div>
                                            </label>
                                        </div>
                                        <span class="font-weight-bold pl-2">Use NickName</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Professional Type</label>
                                    </div>
                                    <div class="col-lg-4">
                                        @foreach($details->userExpertise as $userExpertise)
                                            <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($userExpertise->getProfessionalType->title) ? $userExpertise->getProfessionalType->title : "" }}</p>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Date of Birth</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">{{ Carbon\Carbon::parse($details->userProfile['dob'])->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Gender</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">{{ $details->userProfile['gender'] }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Contact Number</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold"><a href="tel:{{ $details->userProfile['phone'] }}">{{ $details->userProfile['phone'] }}</a></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Email</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold"><a href="mailto:{{ $details->email }}">{{ $details->email }}</a></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Dance/Music Type</label>
                                    </div>
                                    <div class="col-lg-4">
                                        @foreach($details->userDanceMusicTypes as $userDanceMusicType)
                                            <p class="font-weight-bold badge badge-flat border-primary text-primary-600">{{ isset($userDanceMusicType->getDanceType->title) ? $userDanceMusicType->getDanceType->title : "" }}</p>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Location</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">{{ $details->userProfile['userCountry']['name'].','.$details->userProfile['userCity']['name'] }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Facebook</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="font-weight-bold">
                                            @if($details->userProfile['fb_link'])
                                                <a target="_blank" href="{{ $details->userProfile['fb_link'] }}">{{ isset($details->userProfile['fb_link']) ? $details->userProfile['fb_link']  : " - "}}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">Website</label>
                                    </div>
                                    <div class="col-lg-8">
                                        @if($details->userProfile['web_link'])
                                            <p class="font-weight-bold">
                                                <a target="_blank" href="{{ $details->userProfile['web_link'] }}">{{ isset($details->userProfile['web_link']) ? $details->userProfile['web_link'] : " - " }}
                                                </a>
                                                @else
                                                    -
                                                @endif
                                            </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold label-before">About Professional</label>
                                    </div>
                                    <div class="col-lg-8">
                                        {!! ($details->userProfile['description']) ? $details->userProfile['description']  : ' - ' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12 py-0">
                                <div class="row">
                                    <div class="col-lg-3 pr-0">
                                        <label class="font-weight-bold">Status : </label>
                                    </div>
                                    <div class="col-lg-8 pl-0">
                                        @php
                                            $status = array_search($details->is_active,config('constant.USER.STATUS'));
                                            $statusArr = [0 => 'info', 1 => 'success', 2 => 'secondary', 3 => 'danger', 4 => 'danger'];
                                            $text = "<span class='custom-badge badge badge-".$statusArr[$details->is_active]."'>".$status."</span>";
                                            $till = '';
                                            if($details->is_active == 4 && !empty($details->suspended_till)){ //suspended by admin
                                                $format = Carbon\Carbon::createFromFormat('Y-m-d',$details->suspended_till)->format('d/m/Y');
                                                $till = "<br/>till ".$format;
                                            }
                                            $text .= "<span class='suspend-till'>".$till."</span>";
                                        @endphp
                                        {!! $text !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 py-3">

                                <a href="#followers_modal" class="text-color-blue font-weight-bold followers_list" data-toggle="modal" data-backdrop="static" data-id="{{ $details->id }}" data-keyboard="false">{{ $count }} Followers</a>
                            </div>
                            
                            
                            @if(isset($details->logo))
                                <a href="{{ ($details->logo != '')?Helper::images($profilePicOrgDynamicUrl).$details->logo:$defaultProUrl }}" class="fancy-pop-image" data-fancybox="images">
                                    <img class="users_logo" src="{{ ($details->logo != '')?Helper::images($profilePicThumbDynamicUrl).$details->logo:$defaultProUrl }}"/>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="font-weight-bold">Upload Images (upto 5)</label>
                            <div class="row my-gallery">
                                @forelse($details->userGallery as $gallery)
                                    <div class="col-lg-2 image-band mr-2">
                                        <div class="d-inline-block">
                                            <a href="{{ Helper::images($galleryOrgDynamicUrl).$gallery['src'] }}" data-fancybox="gallery-images" class="my-gallery-image">
                                                <img src="{{ Helper::images($galleryThumbDynamicUrl).$gallery['src'] }}" class="upcoming-images" style="width:155px;height:144px;object-fit:cover;">
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <p class="ml-1 pl-1">No Images Found</p>
                                @endforelse
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($is_wallet_acsess == 1)
                    @include('admin.professional.wallet')
                @endif
                @include('admin.professional.booking')
                @include('admin.professional.event')
                @include('admin.professional.bank')
                @include('admin.professional.event_manager')
            </div>
        </div>
    </div>
    <div id="followers_modal" class="modal fade my-feed" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered  modal-sm">
            <div class="modal-content">
                <div class="modal-body p-0 followers-modal-body">
                    <div class="header-elements-inline px-3 py-2 border-bottom-1">
                        <div class="">
                            <h5 class="card-title text-black m-0 font-extra-medium product-regular">Followers</h5>
                        </div>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item followers-close-modal close text-black close" data-action="remove" data-dismiss="modal"></a>
                            </div>

                            <div class="mx-auto">
                                <form name="get-followers" id="get-followers" method="post" action="{{ route('admin.get-followers') }}">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="user_id" id="user_id" class="user_id" value="{{ $details->id }}">
                                    <input type="hidden" name="page" id="page" value="1">
                                    <input type="hidden" name="total" id="total" value="">
                                    <input type="hidden" name="request-in-progress" id="request-in-progress" value="false">

                                    <div class="search-followers-input position-relative mt-lg-0">
                                        <input type="text" name="search_followers" class="form-control search_followers event-icons" id="search_followers" placeholder="Search">
                                        <div class="search-icon">
                                            <i class="flaticon-search"></i>
                                        </div>
                                    </div>

                                    <input type="submit" class="d-none">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="professional-followers-listing-div p-3">
                </div>
                <div class="col-md-12 text-center">
                    <a href="javascript:void(0)" class="loadMore d-none">Load More</a>
                    <a href="javascript:void(0)" class="loading d-none">Loading...</a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.add_money')
@endsection
@section('footer_content')
<script src="{{ Helper::assets('js/pages/admin/admin_dancer_common.js') }}"></script>
<script src="{{ Helper::assets('js/pages/admin/admin_report.js') }}"></script>
<script src="{{ Helper::assets('js/pages/admin/remove_user.js') }}"></script>
<script src="{{ Helper::assets('js/pages/admin/admin_event.js') }}"></script>
<script>
    var remove_user = "{{ route('remove-user') }}";
    var dancer_list = "{{ route('admin.dancer.index') }}";
    var professional_list = "{{ route('admin.professional.index') }}";
    var walletTable = "";
    var eventManagerTable = "";
    var filter = "{{ route('admin.wallet.filter') }}";
    var event_filter_link = "{{ route('admin.myevents.filter') }}";
    var event_manager_filter_link = "{{ route('admin.event-manager.filter') }}";
    var change_event_manager_status_link = "{{ route('admin.event-manager.change.status') }}";
    var add_money_wallet_link = "{{ route('admin.addMoney.wallet') }}";
    var change_status_link = "{{ route('admin.user.status') }}";
    var change_event_status_link = "{{ route('admin.events.status') }}";
    var is_nickname_check_link = "{{ route('admin.check.nickname') }}";
    var change_report_status_link = "{{ route('admin.report.status') }}";
    var delete_event_link = "{{ route('admin.delete.event') }}";
    var booking_section_link = "{{ route('admin.booking.section') }}";
    var is_detail = 1;
    var url = window.location.href.split(1);
    var type = null;
    if(window.location.href.indexOf("professional-details") > -1){
        type = 3;
    }

        $(document).ready( function () {
            walletTable = $('#wallet-datatable').DataTable({
                serverSide: true,
                bFilter:false,
                ajax: {
                    url: filter,
                    type: 'POST',
                    beforeSend: function(){
                        $('body').block({
                            message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    data: function (d) {
                        d.dates = $('#wallet_dates').val();
                        d.keyword = $('#keyword').val();
                        d.is_filtered = $('.wallet_is_filtered').val();
                        d.id = $('.id').val();
                    },
                    complete: function(){
                        $('body').unblock();
                    },
                },
                columns: [
                    { data: 'sr_no', name: 'sr_no' ,searchable:false, orderable:false},
                    { data: 'created_at', name: 'created_at' ,searchable:false},
                    { data: 'type', name: 'type' ,searchable:false},
                    { data: 'status', name: 'status' ,searchable:false},
                    { data: 'amount', name: 'amount', searchable:false, orderable:false }
                ]
            });


            eventManagerTable = $('#event_manager_datatable').DataTable({
                serverSide: true,
                bFilter:false,
                ajax: {
                    url: event_manager_filter_link,
                    type: 'POST',
                    beforeSend: function(){
                        $('body').block({
                            message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    data: function (d) {
                        d.keyword = $('#event_manager_keyword').val();
                        d.status = $('#event_manager_status').val();
                        d.id = $('.id').val();
                    },
                    complete: function(){
                        $('body').unblock();
                    },
                },
                columns: [
                    { data: 'sr_no', name: 'sr_no' ,searchable:false, orderable:false},
                    { data: 'name', name: 'name' ,searchable:false},
                    { data: 'email', name: 'email' ,searchable:false},
                    { data: 'assign_events', name: 'assign_events' ,searchable:false},
                    { data: 'status', name: 'status' ,searchable:false},
                    { data: 'actions', name: 'actions', searchable:false, orderable:false }
                ]
            });

            $('#btnManagerFilter').click(function () {
                $('#event_manager_datatable').DataTable().draw(true);
            });

            $('#btnManagerReset').click(function () {
                $('#event_manager_keyword').val('');
                $('#event_manager_status').val('').change();
                $('#event_manager_datatable').DataTable().draw(true);
            });

            eventTable = $('.events-datatable').DataTable({
            serverSide: true,
            bFilter:false,
            ajax: {
                url: event_filter_link,
                type: 'POST',
                beforeSend: function(){
                    $('body').block({
                        message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                        overlayCSS: {
                            backgroundColor: '#000',
                            opacity: 0.15,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });
                },
                data: function (d) {
                    d.dates = $('#event_dates').val();
                    d.keyword = $('#event_keyword').val();
                    d.is_filtered = $('.is_filtered').val();
                    d.id = $('.id').val();
                    d.event_types = $('#event_event_types').val();
                    d.dance_types = $('#event_dance_types').val();
                },
                complete: function(){
                    $('body').unblock();
                },
            },
            columns: [
                { data: 'sr_no', name: 'sr_no' ,searchable:false, orderable:false},
                { data: 'event_name', name: 'event_name' ,searchable:false},
                { data: 'event_type', name: 'event_type' ,searchable:false},
                { data: 'dance_music', name: 'dance_music' ,searchable:false},
                { data: 'dates', name: 'dates', searchable:false, orderable:false },
                { data: 'status', name: 'status', searchable:false, orderable:false },
                { data: 'actions', name: 'actions', searchable:false, orderable:false },
            ]
        });

            $(document).on('change','.is_nickname_use',function(e){
                var isNickName = $(this).data('nickname');
                var id = $(this).data('id');
                var flag = $(this).prop('checked');
                $.ajax({
                    url:is_nickname_check_link,
                    method:'POST',
                    data:{ id:id,isNickName:isNickName,flag:flag},
                    beforeSend: function () {
                        $('body').block({
                            message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    success: function (response) {
                        if (response.status == 200) {
                        } else {
                        }
                    },
                    complete: function () {
                        $('body').unblock();
                    },
                });
            });

            $('[data-dismiss=modal]').on('click', function (e) {
                $('#page').val(1)
                $('#total').val('');
                $('#search_followers').val('');
                $('.professional-followers-listing-div').html('');
            });

            /*
                Start by kaushik
             */
            $('.loadMore').on('click', function () {
                getFollowers();
            });
            $('.followers_list').on('click', function () {
                var current_page = parseInt($('#page').val());
                var total_page = parseInt($('#total').val());
                if(isNaN(total_page))
                    getFollowers();
            });

            $(document).on('keyup', ".search_followers", function () {
                $('#page').val(1)
                $('#total').val('');
                $('.professional-followers-listing-div').html('');
                getFollowers();
            });

            $('.professional-followers-listing-div').on('scroll', function() {
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    getFollowers();
                }
            })
        });
        var xhr = '';
        /*
            Start by kaushik
         */
        function getFollowers(){
            var current_page = parseInt($('#page').val());
            var total_page = parseInt($('#total').val());
            if(isNaN(total_page) || current_page <= total_page ) {
                if(xhr != '')
                    xhr.abort();

                var form = $('#get-followers').ajaxSubmit(
                    {
                        beforeSubmit: showRequest_get_followers,  // pre-submit callback
                        success: showResponse_get_followers,  // post-submit callback
                        error: errorResponse_get_followers,
                    }
                );
                xhr = form.data('jqxhr');
            }else if(current_page > total_page) {
                $('.loadMore').hide();
            }
        }
        // pre-submit callback
        function showRequest_get_followers(formData, jqForm, options) {
            $('.loadMore').hide();
            $('.loading').show();
        }

        // post-submit callback
        function showResponse_get_followers(responseText, statusText, xhr, jqForm)  {
            $('.loading').hide();
            if(responseText.next_page > responseText.total){
                $('.loadMore').hide()
            }else $('.loadMore').show()
            $('#page').val(responseText.next_page);
            $('#total').val(responseText.total);
            $('.professional-followers-listing-div').append(responseText.html);
        }

        function errorResponse_get_followers(xhr, textStatus, errorThrown, jqForm) {
            $('.loading').hide();
            console.log(errorThrown);
            console.log('Unblock UI Error')
        }

    $('#btnEventsFilter').click(function () {
        $('.is_filtered').val(1);
        $('.events-datatable').DataTable().draw(true);
    });

    $('#btnEventsReset').click(function () {
        $('#event_keyword').val('');
        $('#event_dance_types').val('').change();
        $('#event_dates').val('');
        $('#event_event_types').val('').change();
        $('.is_filtered').val('');
        $('.events-datatable').DataTable().draw(true);
    });

    
    $('#btnWalletFilter').click(function () {
        $('.wallet_is_filtered').val(1);
        $('#wallet-datatable').DataTable().draw(true);
    });

    $('#btnWalletReset').click(function () {
        $('#wallet_dates').val('');
        $('.wallet_dates_is_filtered').val('');
        $('#status').val('').change();
        $('#wallet-datatable').DataTable().draw(true);
    });

    </script>
@endsection
