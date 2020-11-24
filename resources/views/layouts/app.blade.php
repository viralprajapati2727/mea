<!DOCTYPE html>
<html>
    <head>
        @include('layouts.partial.head')
        @yield('header-style')
    </head>
    <body class="body" id="body">
        @include('layouts.partial.nav')
        <div id="loading">
            <div class="loader-content positin-relative">
                <img src="{{ Helper::assets('images/loding_original.gif') }} " class="loading-image  mx-auto">
            </div>
        </div>
        @include('layouts.flash-message')
        @yield('content')
        @include('layouts.partial.footer')
        @yield('footer_script')
    </body>
</html>
