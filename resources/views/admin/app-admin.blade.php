<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <!-- <link href="{{ Helper::assets('css/app.css') }}" rel="stylesheet"> -->

    <!-- Global stylesheets -->
    <link rel="shortcut icon" href="{{ Helper::assets('images/favicon.ico') }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/font/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/font/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/font/flaticons/flaticon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/admin_custom.css') }}" rel="stylesheet" type="text/css">

    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{Helper::assets('js/main/jquery.ui.min.js')}}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/loaders/blockui.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>

    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/styling/switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.fancybox.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/selects/select2.min.js') }}"></script>
    
    
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ Helper::assets('js/app.js') }}"></script>
    <!-- /theme JS files -->

    @yield('header_content')
</head>

<body class="admin">
    <div id="app">
        @include('layouts.partial.admin_main_navbar')
        <!-- Page content -->
        <div class="page-content">
            @if(Auth::check())
                @include('layouts.partial.admin_sidebar')
            @endif
            <!-- Main content -->
            <div class="content-wrapper">

                @yield('page-header')
                 {{-- <div id="loading">
                    <i class="icon-spinner6 spinner mx-auto" id="loading-image"></i><br>
                </div>  --}}
                <!-- /page header -->

                <!-- Content area -->
                @include('layouts.flash-message')
                <div class="content">
                    @yield('content')
                </div>
                <!-- /content area -->

                @include('layouts.partial.admin_footer')

            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>

    @yield('footer_content')
    <script type="text/javascript">
        var base_url = "{{ url('/admin') }}/";
        var rpp = '@php echo config('constant.rpp') @endphp'
        $(document).ready(function(){
            var min_height = $(window).height() - ($('.navbar').height() + 2);
            $('.page-content').css('min-height',min_height);
        });
        var csrfToken = $('[name="csrf-token"]').attr('content');
        function refreshToken(){
            $.get('{{ url("refresh-csrf") }}').done(function(data){
                $('[name="csrf-token"]').attr('content',data); // the new token--}}
            });
        }
        setInterval(refreshToken, 100000); // 2 minutes

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
   </script>
</body>
</html>
