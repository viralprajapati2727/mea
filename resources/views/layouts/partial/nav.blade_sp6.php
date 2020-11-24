@php

$show_login = false; $authtype = "login";
$user = Auth::user();
$redirection = $myProfileRedirection = "javascript:;";
$profilePicOrgDynamicUrl = str_replace('{userSlug}', isset($user)?$user->slug:'-', config('constant.profile_url'));
$profilePicThumbDynamicUrl = str_replace('{userSlug}', isset($user)?$user->slug:'-', config('constant.profile_thumb_url'));
$defaultProUrl = Helper::images(config('constant.default_profile_url')).'default.png';
$notificationCount = 0;
$userId = 0;

$profileImage = $defaultProUrl;
if(isset($user)){
    $userId = $user->id;
    $notificationCount = Helper::notificationCount($userId);

    $profileImage = (isset($user->logo) && $user->logo != '')?Helper::images($profilePicThumbDynamicUrl).$user->logo:$defaultProUrl;
    if($user->type == config('constant.USER.TYPE.PROFESSIONAL')){
        $myProfileRedirection = route('professional.fill-profile');
        if($user->is_profile_filled == 0){
            $redirection = route('professional.fill-profile');
        }else{
            $redirection = route('professional.viewProfile',$user->slug);
        }
    } else if($user->type == config('constant.USER.TYPE.DANCER')){
        $redirection = $myProfileRedirection = route('dancer.fill-profile');
    }
}
$profile = "";
if(isset($user->is_profile_filled) && $user->is_profile_filled == 1){
    if(isset($user->userProfile)){
        $profile = $user->userProfile->total_wallet;
    }
}

@endphp
@if (old('authtype'))
    @php $show_login = true; $authtype = old('authtype'); @endphp
    @if($authtype == "login")
        <script>$(document).ready(function(){ $('#sign-in-modal').modal('show'); });</script>
    @elseif($authtype == "register")
        <script>$(document).ready(function(){ $('#sign-up-modal').modal('show'); });</script>
    @elseif($authtype == "resend")
        <script>$(document).ready(function(){ $('#resend-email-in-modal').modal('show'); });</script>
    @elseif($authtype == "reset")
        <script>$(document).ready(function(){ $('#forgot-password-in-modal').modal('show'); });</script>
    @elseif($authtype == "setnewpassword")
        <script>$(document).ready(function(){ $('#set-password-in-modal').modal('show'); });</script>
    @endif
@elseif(isset($popup_open) && $popup_open == "reset")
    <script>$(document).ready(function(){ $('#set-password-in-modal').modal('show'); });</script>
@elseif(isset($popup_open) && $popup_open == "social_register")
    <script>$(document).ready(function(){ $('#select-role-modal').modal('show'); });</script>
@endif
<nav class="navbar navbar-expand-lg bg-white" id="header">
    <div class="container">
        <div class="navbar-brand w-50 w-sm-auto">
            <a href="{{ route('index') }}" class="d-inline-block">
                <img src="{{ Helper::assets('images/logo.png') }}" class="h-auto w-100" alt="">
            </a>
        </div>
        <div class="d-lg-none">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                <i class="icon-paragraph-justify3 icon-2x"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item">
                    <a href="{{ route('index') }}" class="navbar-nav-link">
                        <span class="ml-2">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('about-us') }}" class="navbar-nav-link">
                        <span class="ml-2">About</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('challenges') }}" class="navbar-nav-link">
                        <span class="ml-2">Contests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('news.feed') }}" class="navbar-nav-link">
                        <span class="ml-2">Feed</span>
                    </a>
                </li>
                <li class="nav-item mb-2 mb-lg-0">
                    {{-- @if(isset($user)) --}}
                        <a href="{{ route('search.events') }}" class="navbar-nav-link">
                            <span class="ml-2">Events</span>
                        </a>
                    {{-- @else
                        <a href="javascript:;" class="navbar-nav-link" data-toggle="modal" data-target="#sign-in-modal" id="loginform">Events</a> --}}
                    {{-- @endif --}}
                </li>
                @auth
            </ul>
            <!-- notification and profile dropdown responsive design -->
                <div class="mobile-dropdown d-block d-md-none d-lg-none">
                    <div class="notification-dropdown pb-2">
                            <a href="javascript:;" class="navbar-nav-link position-relative p-0 d-inline-block nav-notification"
                            data-toggle="modal" data-target="#notification" id="notificatin-dropdown">
                            <i class="flaticon-bell text-common-color position-relative"></i>
                            @if($notificationCount > 0)
                            <div class="counting">
                            <div class="notification-count bg-blue text-white">{{ $notificationCount }}</div>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="profile-dropdown-content d-flex align-items-center">

                            <a href="javascript:;" class="navbar-nav-link position-relative p-0 d-inline-block"
                            data-toggle="modal" data-target="#profile" id="notificatin-dropdown">
                            <div class="profile-icon-menu">
                                <div class="profile-bg-image" style="background-image: url('{{ $profileImage }}');"></div>
                            </div>
                        </a>
                    </div>
                </div>
             <!-- notification and profile dropdown responsive design -->
            <div class="main-menuright d-none d-md-block d-lg-flex align-items-center pl-lg-4">
                <ul class="navbar-nav notification">
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle caret-0 jb_company_dd_lang position-relative text-muted nav-notification" href="javascript:void(0);" role="button" data-toggle="dropdown">
                            <i class="flaticon-bell text-common-color position-relative"></i>
                            @if($notificationCount > 0)
                                <div class="counting">
                                    <div class="notification-count bg-blue text-white">{{ $notificationCount }}</div>
                                </div>
                            @endif
                        </a>
                        <!-- <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0 message-show">3</span> -->
                        <span class="tooltip-arrow navigation-tooltip"></span>
                        <div class="dropdown-menu dropdown-menu-right jb_company_dm_lang">
                            <h4 class="pl-3 notification-border pb-2 mb-0">Notifications</h4>
                            <input type="hidden" name="notification_page" id="notification-page" value="1">
                            <input type="hidden" name="notification_total" id="notification-total-page" value="">
                            <div class="notification-body">
                            </div>
                        <h3 class="notification-footer pl-3 m-0 pb-1"><a href="{{ route('all-notification') }}" class="text-link text-common-color notification-read" data-user="{{ $userId   }}">See All</a></h3>
                        </div>
                    </li>
                </ul>
                <div class="profile-dropdown d-flex justify-content-md-start align-items-md-center pl-lg-4">

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle caret-0 profile-menu d-inline-block position-relative  m-0 p-0" href="{{ $redirection }}" role="button" data-toggle="dropdown">
                                <div class="profile-dropdown-content d-flex align-items-center">
                                    <div class="profile-icon-menu">
                                        <div class="profile-bg-image" style="background-image: url('{{ $profileImage }}');"></div>

                                        <!-- <img src="images/Home/profile_image.jpg"> -->
                                    </div>
                                    <span class="down-arrow"></span>
                                </div>

                            </a>
                            <!-- <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0 message-show">3</span> -->
                            <span class="tooltip-arrow navigation-tooltip"></span>
                            <div class="dropdown-menu dropdown-menu-right dancer-profile-menu">
                                <div class="card-body">
                                    <div class="media align-items-center d-flex d-lg-flex">
                                        <div class="profile-icon-menu pr-2">
                                            <a href="{{ $redirection }}" class="d-inline-block">
                                                <div class="profile-bg-image" style="background-image: url('{{ $profileImage }}');"></div>
                                            </a>
                                        </div>
                                        <!-- <div class="user-icon d-flex justify-content-center align-items-center position-relative">
                                            <div class="profile-bg-image" style="background-image: url(images/Home/profile_image.jpg);"></div>
                                        </div> -->
                                        <div class="media-body dancer-content">
                                            <a href="{{ $redirection }}" class="d-inline-block">
                                                <h3 class="text-black username">{{ isset($user) ? (($user->is_nickname_use == 0) ? ((isset($user->name) && !empty($user->name)) ? $user->name : "Welcome") : $user->nick_name) : "Welcome" }}</h3>
                                            </a>
                                            <p class="text-custom-muted email-text">{{ isset($user->email) ?  $user->email  : ""}}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-overflow">
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('user.my-wallet') }}" class="dropdown-item ">My Wallet <span class="wallet-balance text-white bg-blue">{{ config('constant.USD') }} {{ isset($user->userProfile->total_wallet) ? $user->userProfile->total_wallet : "0"  }}</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @if(isset($user))
                                        @if($user->type == config('constant.USER.TYPE.DANCER'))
                                            <a href="{{ route('search.professionals') }}" class="dropdown-item ">Browse Professionals</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('myTickets') }}" class="dropdown-item ">My Tickets</a>
                                        @else
                                        @if(isset($user->is_profile_filled) && $user->is_profile_filled == 1)
                                                <a href="{{ route('create_event') }}" class="dropdown-item">Create Event</a>
                                                <a href="{{ route('professional.viewProfile',$user->slug) }}#myevent#ongoingevent" class="dropdown-item myevent_a event_nav">My Events</a>
                                            @else 
                                                <a href="javascript:void(0);" data-flag="CreateEvent" class="dropdown-item noProfileFilled">Create Event</a>
                                                <a href="javascript:void(0);" class="dropdown-item noProfileFilled myevent_a event_nav" data-flag="MyEvent">My Events</a>
                                        @endif
                                            <a href="{{ route('myTickets') }}" class="dropdown-item ">My Tickets</a>
                                            <a href="{{ route('professional.sponsered-event',Auth::user()->slug) }}" class="dropdown-item ">Sponsored Events</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('search.professionals') }}" class="dropdown-item ">Browse Professionals</a>
                                            <a href="{{ route('event_manager_list') }}" class="dropdown-item ">Event Managers</a>
                                            <div class="dropdown-divider"></div>
                                                <a href="{{ route('challenges')}}" class="dropdown-item ">Contests</a>
                                                <a href="{{ route('professional.viewProfile',$user->slug) }}#myentries" class="dropdown-item myentries_a entry_nav">My Entries</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('news.feed') }}" class="dropdown-item">Social Feed</a>
                                            <a href="{{ route('professional.viewProfile',$user->slug) }}#myfeeds" class="dropdown-item feed_nav">My Feed</a>

                                        @endif
                                    @endif
                                    <div class="dropdown-divider"></div>
                                        <a href="{{ $myProfileRedirection }}" class="dropdown-item ">Edit Profile</a>

                                    @if(isset($user->is_profile_filled) && $user->is_profile_filled == 1)
                                        <a href="{{ route('user.my-account') }}" class="dropdown-item ">Settings</a>
                                    @else
                                        <a href="javascript:void(0);" class="dropdown-item noProfileFilled" data-flag="Settings">Settings</a>
                                    @endif
                                    <a href="javascript:void(0);" class="dropdown-item " onclick="event.preventDefault();document.getElementById('mobile-logout-form').submit();">Logout</a>
                                    <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" autocomplete="off">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
                @else
                    <li class="nav-item">
                        <div class="">
                            <a href="javascript:;" class="navbar-nav-link btn custom-btn header-btn member-login-btn ml-0 ml-lg-2 justify-content-center text-white rounded-lg mr-3 mr-lg-0" data-toggle="modal" data-target="#sign-in-modal" id="loginform"><i class="flaticon-user-2 mr-1"></i> MEMBER LOGIN</a>
                        </div>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <div class="buttons-log">
                            <a href="javascript:;" class="navbar-nav-link btn custom-btn signup-btn ml-0 ml-lg-2 justify-content-center text-white rounded-lg ml-0 ml-md-3 ml-3" data-toggle="modal" data-target="#sign-up-modal" id=""><i class="flaticon-lock mr-1"></i> SIGN UP</a>
                            <!-- <button type="submit" class="navbar-nav-link btn custom-btn signup-btn ml-0 ml-lg-2 justify-content-center text-white rounded-lg" id="signbutton"><i class="flaticon-lock mr-1"></i> SIGN UP</button> -->
                        </div>
                    </li>
            </ul>
                @endauth
        </div>
    </div>
</nav>

@include('layouts.partial.auth-modal')
{{-- mobile notification dropdown --}}
<div id="notification" class="modal dcr_modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered  signup_popup modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                       <div class="notification-inner-content">
                            <h3 class="notification-border card-body">Notifications</h3>
                                <div class="notification-body">
                                </div>
                                <h3 class="notification-footer pl-3 m-0 pb-1"><a href="{{ route('all-notification') }}" class="text-link text-common-color font-small notification-read" data-user="{{ $userId   }}">See All</a></h3>
                       </div>
                    <div class="jb_close_btn">
                            <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                                <i class="flaticon-close"></i>
                            </button>
                        </div>
                </div>
            </div>
        </div>
</div>
{{-- mobile notification dropdown --}}
{{-- mobile profile dropdown --}}
<div id="profile" class="modal dcr_modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered  signup_popup modal-lg">
            <div class="modal-content">
                <div class="modal-body profile-body p-0">
                       <div class="profile-inner-content">
                            <div class="card-body">
                                    <div class="media align-items-center d-flex d-lg-flex">
                                        <div class="profile-icon-menu pr-2">
                                            <a href="dancer-edit-profile.php" class="d-inline-block">
                                                <div class="profile-bg-image" style="background-image: url(images/Home/profile_image.jpg);"></div>
                                            </a>
                                        </div>
                                        <div class="media-body dancer-content line-height-normal">
                                            <a href="{{ $redirection }}" class="d-inline-block line-height-normal">
                                                <h3 class="text-black username">{{ isset($user) ? (($user->is_nickname_use == 0) ? ((isset($user->name) && !empty($user->name)) ? $user->name : "Welcome") : $user->nick_name) : "Welcome" }}</h3>
                                            </a>
                                            <p class="text-custom-muted email-text m-0 line-height-normal">{{ isset($user->email) ?  $user->email  : ""}}</p>
                                        </div>
                                    </div>
                            </div>
                            <div class="profile-overflow">
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('user.my-wallet') }}" class="dropdown-item ">My Wallet <span class="wallet-balance text-white bg-blue">{{ isset($user->userProfile->total_wallet) ? $user->userProfile->total_wallet : "0"  }} {{ config('constant.USD') }}</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ url('create_event') }}" class="dropdown-item ">Create Events</a>
                                    @if(isset($user))
                                        @if($user->type == config('constant.USER.TYPE.DANCER'))
                                            <a href="{{ route('search.professionals') }}" class="dropdown-item ">Browse Professionals</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('myTickets') }}" class="dropdown-item ">My Tickets</a>
                                        @else
                                            @if(isset($user->is_profile_filled) && $user->is_profile_filled == 1)
                                                <a href="{{ route('professional.viewProfile',$user->slug) }}#myevent#ongoingevent" class="dropdown-item myevent_a event_nav">My Events</a>
                                            @endif
                                            <a href="{{ route('myTickets') }}" class="dropdown-item ">My Tickets</a>
                                            <a href="{{ route('professional.sponsered-event',Auth::user()->slug) }}" class="dropdown-item ">Sponsored Events</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('search.professionals') }}" class="dropdown-item ">Browse Professionals</a>
                                            <a href="{{ route('event_manager_list') }}" class="dropdown-item ">Event Managers</a>
                                            <div class="dropdown-divider"></div>
                                                <a href="{{ route('challenges') }}" class="dropdown-item ">Contests</a>
                                                <a href="{{ route('professional.viewProfile',$user->slug) }}#myentries" class="dropdown-item myentries_a entry_nav">My Entries</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('news.feed') }}" class="dropdown-item">Social Feed</a>
                                            <a href="{{ route('professional.viewProfile',$user->slug) }}#myfeed" class="dropdown-item feed_nav">My Feed</a>
                                        @endif
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ $myProfileRedirection }}" class="dropdown-item">Edit Profile</a>
                                    @if(isset($user->is_profile_filled) && $user->is_profile_filled == 1)
                                        <a href="{{ route('user.my-account') }}" class="dropdown-item ">Settings</a>
                                    @else
                                        <a href="javascript:void(0);" class="dropdown-item noProfileFilled" data-flag="Settings">Settings</a>
                                    @endif
                                    <a href="javascript:void(0);" class="dropdown-item " onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" autocomplete="off">
                                        @csrf
                                    </form>
                                </div>
                       </div>
                    <div class="jb_close_btn">
                            <button type="button" class="close jb_md_close popupclosebutton" data-dismiss="modal">
                                <i class="flaticon-close"></i>
                            </button>
                        </div>
                </div>
            </div>
        </div>
</div>
{{-- mobile profile dropdown --}}

<div class="uload-section bg-white d-none">
    <div class="upload-header px-4 py-2">
        <div class="row align-items-center">
            <div class="col-8">
                <p class="text-white font-small m-0">Upload progress</p>
            </div>
            <div class="col-4 p-lg-0">
                <div class="right-content text-right">
                    <div class="icon-group d-flex align-items-center justify-content-end">
                        <a href="javascript:void(0)" class="d-flex align-items-center justify-content-center mx-2 border-radius-full">
                            <i class="flaticon-arrow"></i></a>
                        {{-- <a href="javascript:void(0)" class="close-icon d-flex align-items-center justify-content-center mx-0 border-radius-full">
                            <i class="flaticon-close"></i></a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="upload-inner-content px-4 py-2">
        
    </div>
</div>
<div class="file-uploading-progress-div d-none">
    <div class="row align-items-center entry_#filename#">
        <div class="col-8">
            <p class="text-black m-0 d-flex align-items-center"><i class="flaticon-clapper mr-2 font-light icon-f"></i>#filename#</p>
        </div>
        <div class="col-4">
            <div class="right-content float-right d-flex">
                <a href="javascript:void(0);" class="complete-upload__uniqidName complete-upload border-radius-full d-flex align-items-center justify-content-center mr-2 cusdnone"><i class="flaticon-check"></i></a>
                <div class="progress__uniqidName progress mx-auto progress-cusk " data-value='60'>
                    <span class="progress-left">
                        <span class="progress-bar border-primary"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar border-primary"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- entry-detail-pop-up start --}}
<div id="entry-detail-pop-up" class="modal dcr_modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
       <div class="modal-content">
          <div class="modal-body entries-modal-body p-2 p-md-4 p-lg-4 entry-modal-data">
            <div class="col-md-4 mx-auto">
                <a href="javascript:void(0)" class="loadMore custom-btn member-login-btn-border justify-content-center text-common-color px-4 rounded-lg">Load More</a>
                <a href="javascript:void(0)" class="loading">Loading...</a>
            </div>
          </div>
       </div>
    </div>
</div>
{{-- entry-detail-pop-up end --}}


<script type="text/javascript">
    var get_notifications = '{{ route("get-notification") }}';
    var read_notification = '{{ route("read-notification") }}';
    var mark_clicked_notification = '{{ route("mark-clicked-notification") }}';
    var fill_profile_first = '{{ route("professional.fill-profile") }}';
    // var entry_details = '{{ route("entry.detail") }}';
    // var upvoteEntries = '{{ route("entry.upvote") }}';
    var user_id = '{{ $userId }}';
    // $(document).on('click','.event_nav',function () {  
    //     $('.my-event-tab').trigger('click');
    // })
    //$(document).on('click','.entry_nav',function () {  
        //$('.myEntryAjaxCall').trigger('click');
    //})
    $(document).on('click','.feed_nav',function () {  
        $('.myfeeds_a').trigger('click');
    })
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/notifications.js') }}"></script>

