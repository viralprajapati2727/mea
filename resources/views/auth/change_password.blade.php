@extends('layouts.app')
@section('content')
<div class="container">
    <div class="form-wrap resetpass-wrap">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card">
                <div class="card-header">{{ __('Change Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('user.update-password') }}" class="change-password-form">
                        @csrf

                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('Current Password') }}</label>

                            <div class="col-md-12">
                                <input id="current_password" type="password" placeholder="Current Password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="current-password">

                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-12">
                                <input id="new_password" type="password" placeholder="New Password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" autocomplete="new-password">

                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirm_password" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="confirm_password" type="password" placeholder="Confirm Password" class="form-control" name="confirm_password" autocomplete="new-password">
                            </div> 
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Change Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div> 
</div>
@endsection
@section('footer_script')
	<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/validation/additional_methods.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf(" ") < 0 && value != "";
            });
            $(".change-password-form").validate({
                ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
                errorClass: 'error',
                successClass: 'validation-valid-label',
                highlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                // Different components require proper error label placement
                errorPlacement: function (error, element) {
                    // Styled checkboxes, radios, bootstrap switch
                    if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                        if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                            error.appendTo(element.parent().parent().parent().parent());
                        }
                        else {
                            error.appendTo(element.parent().parent().parent().parent().parent());
                        }
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
                validClass: "validation-valid-label",
                success: function (label) {
                    label.remove();
                },
                rules: {
                    current_password: {
                        required: true,
                        noSpace: true,
                    },
                    new_password: {
                        required: true,
                        noSpace: true,
                        minlength: 6,
                    },
                    confirm_password: {
                        required: true,
                        noSpace: true,
                        equalTo: "#new_password"
                    },
                },
                messages: {
                    current_password: {
                        required: "Please enter old password",
                        noSpace: "Space are not allowed",
                    },
                    new_password: {
                        required: "Please enter new password",
                        noSpace: "Space are not allowed",
                        minlength: "At Least {0} characters required",
                    },
                    confirm_password: {
                        required: "Please enter confirm password",
                        noSpace: "Space are not allowed",
                        equalTo: "Password and Confirm Password does not match",
                    },
                },
                submitHandler: function (form) {
                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    form.submit();
                }
            });
        });
    </script>
@endsection
