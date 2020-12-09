$(document).ready(function () {
    var _this_wallet = '';
    $('#btnFilter').click(function () {
        $('.is_filtered').val(1);
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function () {
        $('#keyword').val('');
        $('#dates').val('');
        $('.is_filtered').val('');
        $('#status').val('').change();
        $('#datatable').DataTable().draw(true);
    });

    $(document).on('click', '.user-status', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var active = $this.attr('data-status');
        var deactive = (active == 1 ? 2 : 1);
        var active_label = (active == 1 ? "ACTIVE" : "DEACTIVE");
        var dialog_title = (status == 1 ? "Are you sure you want to deactivate the user?" : "Are you sure you want to activate the user?");

        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: active_link,
                    type: 'POST',
                    data: { id : id, active : active},
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                if(deactive == 0){
                                    $this.parent('span').removeClass('badge-danger');
                                    $this.parent('span').addClass('badge-success');
                                }else{
                                    $this.parent('span').addClass('badge-danger');
                                    $this.parent('span').removeClass('badge-success');
                                }
                                $this.html(active_label);
                                $this.attr('data-active',deactive);
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                });
            }
        });
    });

    $(document).on('click', '.add_money', function () {
        var $this = $(this);
        _this_wallet = $this;
        var id = $this.data('id');
        var name = $this.data('name');
        $('.user_id').val(id);
        $('.user_name').val(name);
        $('.users_name').text(name);
    })

    $(document).on('click', '.custom_close', function () {
        $('.validation-invalid-label').remove();
        $("#dance_music_type_form").trigger("reset");
        $('.user_id').val('');
        $('.user_name').val('');
        $('.add_money_input').val('');
    })


    $.validator.addMethod("doubleVal", function (value, element) {
        return this.optional(element) || /^([1-9]\d{0,5})(.\d{1,2})?$/.test(value);
    });
    $(".add_money_form").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-invalid-label',
        // successClass: 'validation-valid-label',
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
                    error.appendTo(element.parent().parent().parent());
                }
            }

            // Input group, styled file input
            else if (element.parents('.form-group').find('.title-error-msg')) {
                error.appendTo(element.parents('.form-group').find('.title-error-msg'));
            }
            else {
                error.insertAfter(element);
            }
        },
        // validClass: "validation-valid-label",
        success: function (label) {
            label.remove();
        },
        rules: {
            add_money_input: {
                required: true,
                doubleVal: true,
            },
        },
        debug: true,
        messages: {
            add_money_input: {
                required: "Please enter money",
                doubleVal: "Money must be entered in digits",
            },
        },
        submitHandler: function (form) {
            $.ajax({
                url: add_money_wallet_link,
                method: 'POST',
                data: $(form).serialize(),
                beforeSend: function () {
                    $('body').block({
                        message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                        overlayCSS: {
                            backgroundColor: '#000',
                            opacity: 0.15,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });
                },
                success: function (response) {
                    if (response.status == 200) {
                        swal({
                            title: response.msg_success,
                            confirmButtonColor: "#66BB6A",
                            type: "success",
                            confirmButtonText: 'OK',
                            confirmButtonClass: 'btn btn-success',
                        }).then(function () {
                            if ($('.wallet-money').attr('data-flag') == 0) {
                                $('#add_money_modal').modal('hide');
                                $('.wallet-money').text(response.total_wallet + ' USD');
                                $('#add_money_form')[0].reset();
                            } else {
                                $('#add_money_modal').modal('hide');
                                _this_wallet.parents('tr').find('.wallet-money').text(response.total_wallet + ' USD');
                                $('#add_money_form')[0].reset();
                            }

                            $('#datatable').DataTable().draw(true);
                        });
                    } else {
                        swal({
                            title: response.msg_fail,
                            confirmButtonColor: "#EF5350",
                            confirmButtonClass: 'btn btn-danger',
                            type: "error",
                            confirmButtonText: 'OK',
                        });
                    }
                },
                complete: function () {
                    $('body').unblock();
                },
            });
        },
    });
})