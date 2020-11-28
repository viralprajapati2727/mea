<header class="site-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="row w-100">
                <div class="col-md-4">
                    <div class="navbar-brand">
                        <img src="{{ Helper::assets('images/logo.png') }}" class="logo" alt="">
                        <h1>MUSLIM ENTREPRENEUR ASSOCISTION</h1>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="header-right">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Community</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Members</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Browse Jobs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Resources</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Startup Portal</a>
                            </li>
                        </ul>
                    </div>
                    <div class="login-links">
                        <ul>
                            <li>
                            @auth
                                <li>
                                    <div class="logout-wrap">
                                        <a class="logoutconfirm" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" autocomplete="off">
                                            @csrf
                                        </form>
                                        <div class="profile-image">
                                        <img src="{{ Helper::assets('images/blog/blog03.jpg') }}" alt="" class="w-100">
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
            </div>
        </nav>
    </div>
</header>