@extends('admin.app-admin')
@section('title') Categories @endsection
@section('page-header')
<!-- Page header -->
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('question-category.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Categories</a>
                <span class="breadcrumb-item active">Category Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Category</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_category"><i class="icon-plus2"></i> Add New Category</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <div class="event-search-icon">
                        <i class="flaticon-search"></i>
                    </div>
                    <label class="text-secondary">Title</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-3 padding-top-class pt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state" id="datatable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div id="add_modal" class="add_modal modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title dance_title">Add Category</h5>
                <button type="button" class="close custom_close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('question-category.store') }}" method="POST" name="category_form" id="category_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label pl-2 dance_label">Add Category:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="title" placeholder="Enter Category" class="form-control category "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <input type="hidden" name="id" class="dance_type_id" id="d_type_id" value="">
                    <div class="form-group row">
                        <label class="font-small font-light col-form-label pl-2 col-md-3">Status: </label>
                        <div class="my-2">
                            <div class="col-md-9">
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="status" class="custom-control-input" id="custom_checkbox_stacked_unchecked" value="1" checked>
                                    <label class="custom-control-label" for="custom_checkbox_stacked_unchecked">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link custom_close" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn bg-primary add-question-category" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_content')
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>

    var faqTable = "";
    var addDanceType = "{{ route('question-category.create') }}";
    var storeDanceType = "{{ route('question-category.store') }}";
    var redirect_link = "{{ route('question-category.index') }}";
    var active_link = "{{ route('admin.change-question-category-status') }}";

    var filter = "{{ route('question-category-filter') }}";
    var oldImage = '';
    var flag = 1;


$(document).ready( function () {
    faqTable = $('#datatable').DataTable({
        serverSide: true,
        bFilter:false,
        ajax: {
            url: filter,
            type: 'POST',
            beforeSend: function(){
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
            data: function (d) {
                d.keyword = $('#keyword').val();
            },
            complete: function(){
                $('body').unblock();
            },
        },
        columns: [
            // { data: 'sr_no', name: '' ,searchable:false, orderable:false},
            { data: 'title', name: 'title' ,searchable:false, orderable:false},
            { data: 'src', name: 'src' ,searchable:false},
            { data: 'status', name: 'status' ,searchable:false},
            { data: 'action', name: 'action', searchable:false, orderable:false }
        ]
    });


    $(document).on('click','.openDanceTypePopoup',function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var src = $(this).data('src');
        $('.add_modal').modal({backdrop: 'static', keyboard: false});
        var path = "";
        var final = path+src;
        $('.add_modal .dance_type_id').val(id);
        var danceId = $('.dance_type_id').val();
        var oldSrc = $('.old_src').val(src);
        if(danceId){
            $('.add_modal .category').val(title);
            $('.append-image').append('<a class="fancy-pop-image" data-fancybox="images" href='+final+'><img class="image-preview-logo mt-3 ml-2" name="previewpic" id="previewpic"  src='+final+'></a>');
            $('.append-image').css('display','block');
        }
    })

    $(document).on('click','.custom_close',function(){
        setTimeout(function(){
            $('.validation-invalid-label').remove();
            $("#category_form").trigger("reset");
            $('.dance_type_id').val(null);
            $('.add_modal .category').val('');
            $('.add_modal .old_src').val('');
            $('.append-image').html('');
        }, 700);
    });

    $(document).on('click','.add_category',function(){
        $('.dance_title').text('Add Category');
        $('.dance_label').html('Add Category: <span class="text-danger">*</span>');
    })

    $(document).on('click','.edit_category',function(){
        var $this = $(this);
        $('.dance_title').text('Edit Category');
        $('.dance_label').html('Edit Category: <span class="text-danger">*</span>');
        if($this.closest('tr').find('.type-status').attr('data-active') == 1){
            $('#custom_checkbox_stacked_unchecked').removeAttr('checked');
        }else {
            $('#custom_checkbox_stacked_unchecked').attr('checked', true);
        }
        oldImage = $('.old_src').val();
        $('input[name="src"]').each(function () {
            $(this).rules('add', {
                required: (oldImage.length == 0) ? true : false,
                normalizer: function (value) { return $.trim(value); },
                messages: {
                    required: "Please select Category image",
                }
            });
        });
    });

    $(document).on('click','.type-status',function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var active = $this.attr('data-active');
        var deactive = (active == 1 ? 0 : 1);
        var active_label = (active == 1 ? "ACTIVE" : "INACTIVE");
        var dialog_title = (active == 1 ? "Are you sure you want to active category?" : "Are you sure you want to deactive category?");

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

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#faq_category').val('').change();
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });


    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
    });



    $(document).on('click','.dancetype_deleted', function() {
        var id= $(this).data('id');
        var deleted= $(this).data('inuse');
        var challengeDanceType = $(this).data('challengedancetype');
        if(challengeDanceType != ""){
            swal({
                title: "This dance type is used in one of the active challenges, you can't delete it!",
                type: 'warning',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-success',
                buttonsStyling: false
            }).then(function (confirm) {
            });
        }else{

            var dialog_title = 'Are you sure want to delete this dance type ?';
            if(deleted != ''){
                dialog_title = 'This dance type is already in use, Are you sure want to delete this dance type?'
            }
            var faqTable_row = $(this).closest("tr");
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
                        url: 'dance-type/'+id,
                        type: 'DELETE',
                        data: { id : id, },
                        beforeSend: function(){
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
                        success: function(response) {
                            if(response.status == 200){
                                swal({
                                    title: response.msg_success,
                                    confirmButtonColor: "#66BB6A",
                                    type: "success",
                                    confirmButtonText: 'OK',
                                    confirmButtonClass: 'btn btn-success',
                                }).then(function (){
                                    faqTable.row(faqTable_row).remove().draw(false);
                                });
                            } else if(response.status == 201){
                                swal({
                                    title: response.msg_success,
                                    confirmButtonColor: "#FF7043",
                                    confirmButtonClass: 'btn btn-warning',
                                    type: "warning",
                                    confirmButtonText: 'OK',
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
                        complete: function(){
                            $('body').unblock();
                        }
                    });
                }
            }, function (dismiss) {
            });
        }
    });

    // Modal template
    var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header align-items-center">\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
        fullscreen: 'btn btn-light btn-icon btn-sm',
        borderless: 'btn btn-light btn-icon btn-sm',
        close: 'btn btn-light btn-icon btn-sm'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross2 font-size-base"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: '',
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'p-2',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: '',
        removeErrorClass: 'text-danger',
        removeIcon: '<i class="icon-bin"></i>',
        indicatorNew: '<i class="icon-file-plus text-success"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };

    // Basic example

    $('.file-input').fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus mr-2"></i>',
        uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
        removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
        },
        initialCaption: "No file selected",
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings
    });

    var danceId = $('.dance_type_id').val();
    var oldSrc = $('.old_src').val();
    $(function () {
        $.validator.addMethod("alpha", function (value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z][\sa-zA-Z]*/);
        });
        $.validator.addMethod("emailValidation", function (value, element) {
            return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,8}$/i.test(value);
        });
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        });
        jQuery.validator.addMethod("extension", function (value, element, param) {
            param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf|doc?x";
            return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
        });

        $("#dance_music_type_form").validate({
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
                title: {
                    required: true,
                    remote:{
                        url: base_url + 'check-unique-types',
                        type:'POST',
                        data:{flag:flag, id:function() { return $('#d_type_id').val(); }},
                    },
                    alpha: true,
                    minlength: 2,
                    maxlength: 20,
                },
                src: {
                    required: oldSrc.length === 0 ? true : false,
                    extension: "jpg|jpeg|png",
                    filesize: 100000,
                    normalizer: function (value) { return $.trim(value); },
                },
            },
            // debug: true,
            messages: {
                title: {
                    required: "Please enter Dance-Music Type Title",
                    remote:"The dance type is already taken",
                    alpha: "The Dance-Music type may only contain letters and spaces.",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                src: {
                    required: "Please select Category image",
                    extension: "Accepted file formats: jpg, jpeg, png",
                    filesize: "File size must be less than 100 KB",
                },
            },
            submitHandler: function (form) {
                $('.add-question-category').attr("disabled", true);
                form.submit();
            }
        });
    });

    $(document).on('change','.custom_dance_type_upload',function(){
        $(this).find('.title-error-msg').children().hide();
    });

    $(document).on('click','.fileinput-remove',function(){
        $('.custom_dance_type_upload').find('.title-error-msg').children().show();
    });

});
   </script>
@endsection
