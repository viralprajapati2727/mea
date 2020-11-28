<!DOCTYPE html>
<html>
    <head>
        @include('layouts.partial.head')
        @yield('header-style')
    </head>
    <body class="body" id="body">
        @include('layouts.partial.nav')
        <div id="loading">
            <i class="icon-spinner10 spinner mx-auto" id="loading-image"></i><br>
        </div>
        @include('layouts.flash-message')
        @yield('content')
        @include('layouts.partial.footer')
        @yield('footer_script')
    </body>
</html>
