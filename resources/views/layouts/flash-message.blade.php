<span class="flash-messages" style=""></span>
@if ($message = Session::get('success'))
<div class="d-block error-message home-globle-error">
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
    <span>{{ $message }}</span>
</div>
</div>
@endif

@if ($message = Session::get('status'))
<div class="d-block error-message home-globle-error">
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
    <span>{{ $message }}</span>
</div>
</div>
@endif

@if ($message = Session::get('error'))
<div class="d-block error-message home-globle-error">
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
    <span>{{ $message }}</span>
</div>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="d-block error-message home-globle-error">
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<span>{{ $message }}</span>
</div>
</div>
@endif


@if ($message = Session::get('info'))
<div class="d-block error-message">
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<span>{{ $message }}</span>
</div>
</div>
@endif


{{-- @if ($errors->any())
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>
	Please check the form below for errors
</div>
@endif
 --}}
