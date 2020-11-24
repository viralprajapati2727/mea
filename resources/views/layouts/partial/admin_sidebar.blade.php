@php
    $sideMenus = Helper::generateMenu();
    $is_admin = false;
    if(Auth::user()->type == 1){
        $is_admin = true;
    }
    // dd($sideMenus);
@endphp
 <!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">
    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                @if(isset($sideMenus) && !empty($sideMenus))
                    @foreach ($sideMenus as $menu)
                        @if($is_admin || $menu['privilege_require'] == 0 || in_array($menu['privilege_key'], $privileges))
                            @if(isset($menu['child']) && !empty($menu['child']) && count($menu['child']))
                                <li class="nav-item nav-item-submenu {{ ((in_array(Route::currentRouteName(),$menu['active_menu'])) ? 'nav-item-open' : '' ) }}">
                                    <a href="javascript:;" class="nav-link {{ ((in_array(Route::currentRouteName(),$menu['active_menu'])) ? 'active' : '' ) }}">
                                        <i class="{{ $menu['icon'] }}"></i>
                                        <span>{{ $menu['short_title'] }}</span>
                                    </a>
                                    <ul class="nav nav-group-sub" style="{{ ((in_array(Route::currentRouteName(),$menu['active_menu'])) ? 'display:block;' : '' ) }}">
                                        @foreach ($menu['child'] as $child)
                                        <li class="nav-item"><a href="{{ $child['url'] }}" class="nav-link {{ ((in_array(Route::currentRouteName(),$child['active_menu'])) ? 'active' : '' ) }}">{{ $child['short_title'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                            <li class="nav-item">
                                <a href="{{ $menu['url'] }}" class="admin_sidebar nav-link {{ ((in_array(Route::currentRouteName(), $menu['active_menu'])) ? 'active' : '' ) }}">
                                    <i class="{{ $menu['icon'] }}"></i>
                                    <span>{{ $menu['short_title'] }}</span>
                                </a>
                            </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->
