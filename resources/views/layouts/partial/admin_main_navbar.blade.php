<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light">
    <div class="navbar-header navbar-{{ Auth::guest() ? 'light' : 'light' }} d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="{{ route('admin.index') }}" class="d-inline-block">
                <img src="{{ Helper::assets('images/logo.png') }}" alt="Logo" width="125">
            </a>
        </div>

        <div class="navbar-brand navbar-brand-xs">
            <a href="{{ route('admin.index') }}" class="d-inline-block">
                <img src="{{ Helper::assets('images/logo.png') }}" alt="Logo" width="125">
            </a>
        </div>
    </div>

	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="icon-tree5"></i>
		</button>
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3"></i>
		</button>
	</div>

	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav">
			@if(Auth::check())
			<li class="nav-item">
				<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
					<i class="icon-paragraph-justify3"></i>
				</a>
			</li>
			@endif
		</ul>

		<span class="navbar-text ml-md-3 mr-md-auto"></span>

		<ul class="navbar-nav">
			@if(Auth::check())
			@php $user = Auth::user(); @endphp
			<li class="nav-item dropdown dropdown-user admin-nav-dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					@php $ProfileUrl = Helper::images(config('constant.profile_url')); $img_url = $ProfileUrl.'default.png'; @endphp
                        @if($user->logo != "")
                            @php
                                if($profile->logo != "")
                                    $is_same_profile_photo = true;

                                $img_url = (isset($user->logo) && $user->logo != '') ? $ProfileUrl . $user->logo : $ProfileUrl.'default.png';
                            @endphp
                        @endif
					<div class="profile-image-nav" style="background-image:url({{ $img_url }}); height:50px; width:50px;background-size:cover;border-radius:50%"></div>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
					@if($user->type == 1)
						<a href="{{ route('admin.fill-profile') }}" class="dropdown-item"><i class="icon-user"></i>My Profile</a>
						<a href="{{ route('admin.change-password') }}" class="dropdown-item"><i class="icon-cog5"></i> Account Settings</a>
					@endif
					<a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"><i class="icon-switch2"></i>Logout</a>
					<form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;" autocomplete="off">@csrf</form>
				</div>
			</li>
			@endif
		</ul>
	</div>
</div>
<!-- /main navbar -->
