<div class="page-top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="top-bar-left">
                    <ul>
                        <li>
                            <i class="flaticon-phone-call"></i>
                            <a href="tel: 516 308 6498">516 308 6498</a>
                        </li>
                        <li>
                            <i class="flaticon-email"></i>
                            <a href="mailto: infomuslimstartups@gmail.com">infomuslimstartups@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="top-bar-right text-right">
                    <div id="google_translate_element"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<header class="site-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="row align-items-center">
                <div class="col-lg-2">
                    <a class="nav-link" href="{{ url('/') }}">
                        <div class="navbar-brand">
                            <img src="{{ Helper::assets('images/logo.png') }}" class="logo" alt="">
                            <h1>MUSLIM ENTREPRENEUR ASSOCISTION</h1>
                        </div>
                    </a>
                </div>
                @auth
                    <div class="col-lg-10">
                        <div class="header-right">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            @if(Auth::check())
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('community.index') }}">Community</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('page.members') }}">Members</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('job.search-job') }}">Browse Requests</a>
                                    </li>
                                    @if(!empty(Helper::getMenuTopics()) && Helper::getMenuTopics()->count())
                                        <li class="nav-item site-nav--has-dropdown">
                                            <a class="nav-link" href="javascript:;">Resources</a>
                                            <div class="site-nav__dropdown">
                                                <div class="site-nav__childlist">
                                                    <ul class="site-nav__childlist-grid">
                                                        @forelse (Helper::getMenuTopics() as $topic)
                                                            <li class="site-nav__childlist-item">
                                                                <a class="site-nav__link site-nav__child-link site-nav__child-link--parent" href="{{ route('page.resources', ['id' => base64_encode($topic->id)]) }}">
                                                                    <span class="site-nav__label">{{ $topic->title }}</span>
                                                                </a>
                                                                <ul>
                                                                    @if(!empty(Helper::getSubTopics()) && Helper::getSubTopics()->count())
                                                                        @forelse (Helper::getSubTopics($topic->id) as $sub_topic)
                                                                            <li>
                                                                                <a href="{{ route('page.resources', ['id' => base64_encode($sub_topic->id)]) }}" class="site-nav__link site-nav__child-link">
                                                                                    <span class="site-nav__label">{{ $sub_topic->title }}</span>
                                                                                </a>
                                                                            </li>
                                                                        @empty
                                                                        @endforelse
                                                                    @endif
                                                                </ul>
                                                            </li>
                                                        @empty
                                                        @endforelse
                                                    </ul> 
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('page.startup-portal') }}">Startup Portal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('page.fund-requests') }}">Fund Request</a>
                                    </li>
                                    <li class="nav-item message-count">
                                        <a class="nav-link" href="{{ route('member.message') }}"><i class="mr-2 fa fa-comment-o"></i><span>{{ Helper::messageCount(Auth::id()) }}</span></a>
                                    </li>
                                </ul>
                            @endif
                            <div class="login-links">
                                <ul>
                                    <li>
                                    @auth
                                        <li>
                                            <div class="logout-wrap">
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" autocomplete="off">
                                                    @csrf
                                                </form>
                                                @php
                                                    $ProfileUrl = Helper::images(config('constant.profile_url'));
                                                    $img_url = (isset(Auth::user()->logo) && Auth::user()->logo != '') ? $ProfileUrl . Auth::user()->logo : $ProfileUrl.'default.png';
                                                @endphp
                                                @if (Auth::check())
                                                <div class="profile-menu">
                                                    <a href="javascript:;" class="profile-menu-link">
                                                        <div class="profile-image">
                                                            <img src="{{ $img_url }}" alt="" class="w-100">
                                                        </div>
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="profile-dropdown dropdown-menu">
                                                        <div class="card-body">
                                                            <div class="media align-items-center d-flex d-lg-flex">
                                                                <div class="profile-icon-menu pr-2">
                                                                    <a href="{{ route('user.view-profile',['slug' => Auth::user()->slug]) }}" class="d-inline-block">
                                                                        <div class="profile-bg-image">
                                                                            <img src="{{ $img_url }}" >
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <div class="media-body mea-content">
                                                                    <a href="#" class="d-inline-block">
                                                                        <h3 class="text-black username">{{ Auth::user()->name }}</h3>
                                                                    </a>
                                                                    <div class="profile-links d-flex">
                                                                        <a href="{{ Auth::user()->type == config('constant.USER.TYPE.SIMPLE_USER') ? route('user.fill-profile') : route('entrepreneur.fill-profile') }}" class="">Edit Profile</a>
                                                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="logoutconfirm">Logout</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="profile-overflow">
                                                            <ul>
                                                                <li><a href="{{ route('user.change-password') }}">Change Password</a></li>
                                                                <li><a href="{{ route('job.fill-job') }}">Post Job</a></li>
                                                                <li><a href="{{ route('job.my-jobs') }}">My Jobs</a></li>
                                                                <li><a href="{{ route('job.applied-job') }}">Applied Jobs</a></li>
                                                                @if (Auth::user()->type != config('constant.USER.TYPE.ADMIN') && Auth::user()->is_active == config('constant.USER.STATUS.Active'))
                                                                    <li><a href="{{ route('startup-portal-request') }}">Startup Portal Request</a></li>
                                                                @endif
                                                                <li><a href="{{ route('page.drop-idea') }}">Drop Your Ideas</a></li>
                                                                <li><a href="{{ route('page.questions') }}">My Questions</a></li>
                                                                @if (Auth::user()->type == config('constant.USER.TYPE.ENTREPRENEUR'))
                                                                    <li><a href="{{ route('startup-portal') }}">StartUp Portal</a></li>
                                                                    <li><a href="{{ route('startup.raise-fund') }}">Startups To Raise Funds</a></li>
                                                                @endif
                                                                <li><a class="logoutconfirm" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif 
                                            </div>
                                        </li>
                                    @else
                                    @endauth
                                    </li>
                                </ul>
                            </div>
                        </div>
                        {{-- <div class="login-links">
                            <ul>
                                <li>
                                @auth
                                    <li>
                                        <div class="logout-wrap">
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" autocomplete="off">
                                                @csrf
                                            </form>
                                            @php
                                                $ProfileUrl = Helper::images(config('constant.profile_url'));
                                                $img_url = (isset(Auth::user()->logo) && Auth::user()->logo != '') ? $ProfileUrl . Auth::user()->logo : $ProfileUrl.'default.png';
                                            @endphp 
                                            <div class="profile-menu">
                                                <a href="javascript:;" class="profile-menu-link">
                                                    <div class="profile-image">
                                                        <img src="{{ $img_url }}" alt="" class="w-100">
                                                    </div>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="profile-dropdown dropdown-menu">
                                                    <div class="card-body">
                                                        <div class="media align-items-center d-flex d-lg-flex">
                                                            <div class="profile-icon-menu pr-2">
                                                                <a href="{{ route('user.view-profile',['slug' => Auth::user()->slug]) }}" class="d-inline-block">
                                                                    <div class="profile-bg-image" style="background-image: url({{ $img_url }});"></div>
                                                                </a>
                                                            </div>
                                                            <div class="media-body mea-content">
                                                                <a href="#" class="d-inline-block">
                                                                    <h3 class="text-black username">{{ Auth::user()->name }}</h3>
                                                                </a>
                                                                <div class="profile-links d-flex">
                                                                    <a href="{{ Auth::user()->type == config('constant.USER.TYPE.SIMPLE_USER') ? route('user.fill-profile') : route('entrepreneur.fill-profile') }}" class="">Edit Profile</a>
                                                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="logoutconfirm">Logout</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="profile-overflow">
                                                        <ul>
                                                            <li><a href="{{ route('user.change-password') }}">Change Password</a></li>
                                                            <li><a href="{{ route('job.fill-job') }}">Post Job</a></li>
                                                            <li><a href="{{ route('job.my-jobs') }}">My Jobs</a></li>
                                                            <li><a class="logoutconfirm" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <a href="{{ route('login') }}">Login Or Register</a>
                                @endauth
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                @else
                    <div class="col-lg-10">
                        <div class="header-right">
                            <a href="{{ route('login') }}" class="header-login">Login Or Register</a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</header>