<header class="site-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="row w-100 align-items-center">
                <div class="col-lg-4">
                    <div class="navbar-brand">
                        <img src="{{ Helper::assets('images/logo.png') }}" class="logo" alt="">
                        <h1>MUSLIM ENTREPRENEUR ASSOCISTION</h1>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header-right">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
                            </li>
                            @if(Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('community.index') }}">Community</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('page.members') }}">Members</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('job.search-job') }}">Browse Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('page.resources') }}">Resources</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('page.questions') }}">Questions</a>
                            </li>
                        </ul>
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
                                                                    <a href="#" class="">Settings</a>
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
                                                                <a href="#" class="">Settings</a>
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
            </div>
        </nav>
    </div>
</header>