    @yield('meta_section')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="x-ua-compatible" content="IE=10">
    <meta name="author" content="Muslim Entrepreneur Association">
    <meta name="title" content="Muslim Entrepreneur Association">
    <meta name="keywords" content="Muslim Entrepreneur Association">
    <meta name="description" content="Muslim Entrepreneur Association">
    <meta property="og:type" content="website" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ Helper::assets('images/favicon.ico') }}">
    <title>{{ config('app.name') }}</title>

     <link href="{{ Helper::assets('css/font/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/font/flaticons/flaticon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/font/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Helper::assets('css/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ Helper::assets('css/style.css') }}" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.ui.min.js')}}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.fancybox.js') }}" ></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/uploaders/fileinput/plugins/purify.min.js') }}" defer></script>
	<script type="text/javascript" src="{{ Helper::assets('js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}" defer></script>
	<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/loaders/blockui.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/validate.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/additional_methods.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/main/jquery.inputmask.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ Helper::assets('js/mea_custom.js') }}"></script>
    @yield('content_head')
    <script language="javascript" type="text/javascript">
        $(window).on('load', function () {
        $('#loading').hide();

        setTimeout(() => {
            var windowHeight = $(window).height();
            var finalModalContentHeight =  parseInt(parseInt(windowHeight) - parseInt((parseInt(windowHeight) * parseFloat(5.5)) / 100));
            $('.set-popup-dynamic .modal-content').height(finalModalContentHeight);
        }, 500);
     });
   </script>
    <script>
    var isMobile = false; //initiate as false
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
            isMobile = true;
        }
        var base_url = "{{ url('/') }}/";
        var csrfToken = $('[name="csrf-token"]').attr('content');
        var image_permitted = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/jpg'];
        var imageSizeLimit = '@php echo config('constant.imageSizeLimit') @endphp'
        var imageSizeLimit_byte = '@php echo config('constant.imageSizeLimit_byte') @endphp'
        var imageSizeLimit_byte_video = '@php echo config('constant.imageSizeLimit_byte_video') @endphp'
        var rpp = '@php echo config('constant.rpp') @endphp'
        var $userc_slug = '{{ isset(Auth::user()->slug)?Auth::user()->slug:'' }}';
        function refreshToken(){
            $.get('{{ url("refresh-csrf") }}').done(function(data){
                $('[name="csrf-token"]').attr('content',data); // the new token--}}
            });
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
