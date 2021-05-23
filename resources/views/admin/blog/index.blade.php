@extends('admin.app-admin')
@section('title') Blogs @endsection
@section('page-header')
<!-- Page header -->
<style>
.date-of-birth-icon {
    position: absolute;
    top: 3px;
    right: 20px;
    color: #767676;
}
</style>
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('blog.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Blogs</a>
                <span class="breadcrumb-item active">Blog Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Blog</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_blog"><i class="icon-plus2"></i> Add New Blog</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
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
                <th>Icon</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div id="add_modal" class="add_modal modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title blog_title">Add Blog</h5>
                <button type="button" class="close custom_close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('blog.store') }}" method="POST" name="blog_form" id="blog_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Title :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="title" placeholder="Enter Title" class="form-control dance_music_type "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Short Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="short_description" id="short_description" rows="5" placeholder="Enter Short Description" class="form-control short_description"></textarea>
                        </div>
                        <div class="input-group short_description-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="description" id="description" rows="5" placeholder="Enter Description" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                    <div class="form-group row uploader custom_blog_upload">
                        <label class="font-small font-light col-form-label pl-2">Upload Blog Image: <span class="text-danger">*</span></label>
                        <div class="photo-band col-md-12">
                            <input type="file" class="file-input dance_type_image" name="src" data-focus/>
                        </div>
                        <input type="hidden" name="id" class="blog_id" id="d_type_id" value="">
                        <input type="hidden" name="old_src" class="old_src" value="">
                        <div class="append-image" style="display: none"></div>
                        <div class="input-group title-error-msg"></div>
                    </div>
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
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Author by :</label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" class="form-control author_by" name="author_by" id="author_by" value="" placeholder="{{ Auth::user()->name }}" >
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Publish Date :</label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" class="form-control published_at" name="published_at" id="date" placeholder="Select Publish Date" value="{{ date("d/m/Y") }}" >
                            <div class="date-of-birth-icon">
                                <i class="flaticon-calendar"></i>
                            </div>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link custom_close" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn bg-primary add-blog" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
        $(document).ready(function(){
        $("#date").datetimepicker({
            ignoreReadonly: true,
            format: 'MM/DD/YYYY',
        }).data('autoclose', true);
    });        


    CKEDITOR.replace('description', {
        height: '200px',
        removeButtons: 'Checkbox,Radio,TextField,Textarea,Select,',
        ttoolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        wordcount: {
            // Whether or not you want to show the Paragraphs Count
            showParagraphs: false,
    
            // Whether or not you want to show the Word Count
            showWordCount: false,
    
            // Whether or not you want to show the Char Count
            showCharCount: true,
    
            // Whether or not you want to count Spaces as Chars
            countSpacesAsChars: false,
    
            // Whether or not to include Html chars in the Char Count
            countHTML: false,
    
            // Maximum allowed Word Count, -1 is default for unlimited
            maxWordCount: -1,
    
            // Maximum allowed Char Count, -1 is default for unlimited
            maxCharCount: 1500,
    
            // Option to limit the characters in the Editor, for example 200 in this case.
            charLimit: 1500,
    
            notification_duration: 1,
            duration: 1
        },
        notification: {
            duration: 1,
            notification_duration: 1
        }
    });

    var blogTable = "";
    var active_link = "{{ route('admin.change-blog-status') }}";

    var filter = "{{ route('blog-filter') }}";
    var oldImage = '';
    var flag = 1;


$(document).ready( function () {
    blogTable = $('#datatable').DataTable({
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


    $(document).on('click','.openBlogPopoup',function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var short_description = $(this).data('short_description');
        var description = $(this).data('description');
        var src = $(this).data('src');
        var published_at = $(this).data("published_at");
        var author_by = $(this).data("author_by");
        $('.add_modal').modal({backdrop: 'static', keyboard: false});
        var path = "{{ Helper::images(config('constant.blog_url')) }}";
        var final = path+src;
        $('.add_modal .blog_id').val(id);
        var blogId = $('.blog_id').val();
        var oldSrc = $('.old_src').val(src);
        // console.log(blogId,'blogId')
        if(blogId){
            $('.add_modal .dance_music_type').val(title);
            $('.add_modal #short_description').text(short_description);
            $('.add_modal #description').text(description);
            CKEDITOR.instances.description.setData(description);
            $('.add_modal #date').val(published_at)
            $('.add_modal #author_by').val(author_by)
            $('.append-image').append('<a class="fancy-pop-image" data-fancybox="images" href='+final+'><img class="image-preview-logo mt-3 ml-2" name="previewpic" id="previewpic"  src='+final+'></a>');
            $('.append-image').css('display','block');
        }
    })

    $(document).on('click','.custom_close',function(){
        // var description = $(this).data('description');
        setTimeout(function(){
            $('.validation-invalid-label').remove();
            $("#blog_form").trigger("reset");
            $('.blog_id').val(null);
            $('.add_modal .dance_music_type').val('');
            $('.add_modal .old_src').val('');
            $('.append-image').html('');
            CKEDITOR.instances['description'].setData("")
            $('.short_description').text('');
            $('.author_by').val('');
            $('#date').val('');
        }, 700);
    });

    $(document).on('click','.add_blog',function(){
        $('.blog_title').text('Add Blog');
        $('.blog_label').html('Add Blog: <span class="text-danger">*</span>');
    })

    $(document).on('click','.edit_blog',function(){
        var $this = $(this);
        $('.blog_title').text('Edit Blog');
        $('.blog_label').html('Edit Blog: <span class="text-danger">*</span>');
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
                    required: "Please select Blog image",
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
        var dialog_title = (active == 1 ? "Are you sure you want to active Blog?" : "Are you sure you want to deactive Blog?");

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



    $(document).on('click','.blog_deleted', function() {
        var id= $(this).data('id');
        var deleted= $(this).data('inuse');
        
        var dialog_title = 'Are you sure want to delete this blog ?';
        var blogTable_row = $(this).closest("tr");
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
                    url: 'blog/'+id,
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
                                blogTable.row(blogTable_row).remove().draw(false);
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

    var blogId = $('.blog_id').val();
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

        $.validator.addMethod("checkCkeditorEmpty", function(value, element, params) {
            var editorcontent = $(params).val().replace(/<[^>]*>/gi, ''); // strip tags
            var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
            if(Number(editor_value) === 0){
                return false;
            };
            return true;
        });

        $("#blog_form").validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            // successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },

            errorPlacement: function(error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }
            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }
            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('multiselect-native-select')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('cv_required')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('ckeditor')) {
                error.appendTo( element.parent().parent());
            }
            else if (element.parents('div').hasClass('file-input-new')) {
                error.appendTo( element.parent().parent().parent());
            }
            // Other elements
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
                    minlength: 2,
                    maxlength: 100,
                },
                short_description: {
                    required: true,
                    minlength: 20,
                    maxlength: 255,
                },
                description: {
                required: function(){
                    CKEDITOR.instances.description.updateElement();
                    var editorcontent = $('#description').val().replace(/<[^>]*>/gi, ''); // strip tags
                    var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
                    return Number(editor_value) === 0;
                },
                checkCkeditorEmpty: '#description',
            },
                src: {
                    required: oldSrc.length === 0 ? true : false,
                    extension: "jpg|jpeg|png",
                    filesize: 1048576,
                    normalizer: function (value) { return $.trim(value); },
                },
            },
            debug: true,
            messages: {
                title: {
                    required: "Please enter blog Title",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                short_description: {
                    required: "Please enter short description",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                description: {
                    required: "Please enter description",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                src: {
                    required: "Please select blog image",
                    extension: "Accepted file formats: jpg, jpeg, png",
                    filesize: "File size must be less than 1 MB",
                },
            },
            submitHandler: function (form) {
                $('.add-blog').attr("disabled", true);
                form.submit();
            }
        });
    });

    $(document).on('change','.custom_blog_upload',function(){
        $(this).find('.title-error-msg').children().hide();
    });

    $(document).on('click','.fileinput-remove',function(){
        $('.custom_blog_upload').find('.title-error-msg').children().show();
    });

});
   </script>
@endsection
