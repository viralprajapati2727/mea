
@extends('layouts.app')
@section('content')
<section>
    <div class="slider">
        <div class="container">
            <div id="owl-demo" class="owl-carousel owl-theme">
                <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div>
                <div class="item"><img src="{{ Helper::assets('images/banner/Slider01.jpg') }}" class="" alt=""></div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer_script')
    <script type="text/javascript">

    </script>
@endsection