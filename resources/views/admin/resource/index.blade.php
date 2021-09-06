@extends('admin.app-admin')
@section('title') Resources @endsection
@section('page-header')
<!-- Page header -->
@php        
 //
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('resource.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Resources</a>
                <span class="breadcrumb-item active">Resource Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Resource</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_resource"><i class="icon-plus2"></i> Add New Resource</a>
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
                <th>Topic/SubTopic</th>
                <th>Icon</th>
                <th>Status</th>
                <th>Resource Order</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div id="add_modal" class="add_modal modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title resource_title">Add Resource</h5>
                <button type="button" class="close custom_close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('resource.store') }}" method="POST" name="resource_form" id="resource_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Title :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="title" placeholder="Enter Title" class="form-control dance_music_type "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row px-2">
                        <label class="col-form-label pl-2">Select Topic/Subtopic:<span class="text-danger">*</span> </label>
                        <select class="form-control form-control-select2" placeholder="Select Topic/Subtpic" name="topic[]" id="topic" multiple>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                            @endforeach
                        </select>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-form-label pl-2">Short Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="short_description" id="short_description" rows="5" placeholder="Enter Short Description" class="form-control"></textarea>
                        </div>
                        <div class="input-group short_description-error-msg"></div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="description" id="description" rows="5" placeholder="Enter Description" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                    <div class="form-group row uploader custom_resource_upload">
                        <label class="font-small font-light col-form-label pl-2">Upload Resource Image: <span class="text-danger">*</span></label>
                        <div class="photo-band col-md-12">
                            <input type="file" class="file-input dance_type_image" name="src" data-focus/>
                        </div>
                        <input type="hidden" name="id" class="resource_id" id="d_type_id" value="">
                        <input type="hidden" name="old_src" class="old_src" value="">
                        <div class="append-image" style="display: none"></div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="font-small font-light col-form-label pl-2 col-md-3">Upload document via URL OR Manual ?: </label>
                        <div class="my-2">
                            <div class="col-md-9">
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_url" class="custom-control-input" id="is_upload_via_url" value="1">
                                    <label class="custom-control-label" for="is_upload_via_url"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row via_url" style="display:none;">
                        <label class="col-form-label pl-2">URL :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="document_url" placeholder="Enter Document/Video URL" class="form-control "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row via_manual">
                        <label class="font-small font-light col-form-label pl-2">Upload Document/Video: <span class="text-danger">*</span></label>
                        <div class="photo-band col-md-12">
                            <input type="file" class="file-input" name="document" data-focus/>
                        </div>
                        <input type="hidden" name="old_document_src" class="old_document_src" value="">
                        <div class="append-document" style="display: none"></div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="font-small font-light col-form-label pl-2 col-md-3">Status: </label>
                            <div class="my-2">
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="status" class="custom-control-input" id="custom_checkbox_stacked_unchecked" value="1" checked>
                                        <label class="custom-control-label" for="custom_checkbox_stacked_unchecked">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-small font-light col-form-label pl-2">
                                Resource Order
                            </label>
                            <div class="my-2">
                                <div class="input-group custom-start col-md-12">
                                    <input type="number" value="" name="resource_order" placeholder="Enter Resource Order Number" class="form-control resource_order"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link custom_close" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn bg-primary add-resource" value="Submit">
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

    CKEDITOR.replace('description', {
        height: '200px',
        removeButtons: 'Subscript,Superscript,Image',
        toolbarGroups: [
            { name: 'styles' },
            { name: 'editing', groups: ['find', 'selection'] },
            { name: 'basicstyles', groups: ['basicstyles'] },
            { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
            { name: 'links' },
            { name: 'insert' },
            { name: 'colors' },
            { name: 'tools' },
            { name: 'others' },
            { name: 'document', groups: ['mode', 'document', 'doctools'] }
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

    var resourceTable = "";
    var active_link = "{{ route('admin.change-resource-status') }}";

    var filter = "{{ route('resource-filter') }}";
    var oldImage = '';
    var flag = 1;


$(document).ready( function () {
    resourceTable = $('#datatable').DataTable({
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
            { data: 'topic_id', name: 'topic_id' ,searchable:false, orderable:false},
            { data: 'src', name: 'src' ,searchable:false},
            { data: 'status', name: 'status' ,searchable:false},
            { data: 'resource_order', name: 'resource_order', searchable: false },
            { data: 'action', name: 'action', searchable:false, orderable:false },
        ]
    });


    $(document).on('click','.openResourcePopoup',function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var short_description = $(this).data('short_description');
        var description = $(this).data('description');
        var src = $(this).data('src');
        var doc = $(this).data('document')
        var topicId = $(this).data('topic')
        topicId = topicId.toString();
        if (topicId.indexOf(',') > -1){
            topicId = topicId.split(',');
        }
        var resourceOrder = $(this).data('resource_order')

        $('.add_modal').modal({backdrop: 'static', keyboard: false});
        var path = "{{ Helper::images(config('constant.resource_url')) }}";
        var document_path = "{{ Helper::images(config('constant.resource_document_url')) }}";
        var final = path+src;
        var finalDocument = document_path+doc;
        $('.add_modal .resource_id').val(id);
        var resourceId = $('.resource_id').val();
        var oldSrc = $('.old_src').val(src);
        var oldDoc = $('.old_document_src').val(doc);
        
        if(resourceId){
            $('.add_modal .dance_music_type').val(title);
            $('.add_modal #short_description').text(short_description);
            $('.add_modal #description').text(description);
            $('#topic').val(topicId).trigger('change');
            CKEDITOR.instances.description.setData(description);
            $('.append-image').append('<a class="fancy-pop-image" data-fancybox="images" href='+final+'><img class="image-preview-logo mt-3 ml-2" name="previewpic" id="previewpic"  src='+final+'></a>');
            $('.append-image').css('display','block');
            if(doc.length > 0){
                $('.append-document').append(`<a class="mx-2" href="${finalDocument}" download>Download Your Resource Document/Video.</a>`);
            }
            $('.append-document').css('display','block');
            $('.resource_order').val(resourceOrder);
        }
    })

    $(document).on('click','.custom_close',function(){
        setTimeout(function(){
            $('.validation-invalid-label').remove();
            $("#resource_form").trigger("reset");
            CKEDITOR.instances.description.setData('');
            $('.resource_id').val(null);
            $('.add_modal .dance_music_type').val('');
            $('.add_modal .old_src').val('');
            $('.add_modal .old_document_src').val('');
            $('.append-image').html('');
            $('.append-document').html('');
            $('#topic').val('').trigger('change');
            $('.resource_order').val('');
        }, 700);
    });

    $(document).on('click','.add_resource',function(){
        $('.resource_title').text('Add Resource');
        $('.resource_label').html('Add Resource: <span class="text-danger">*</span>');
        $('#topic').val('').trigger('change');
    })

    $(document).on('click','.edit_resource',function(){
        var $this = $(this);
        $('.resource_title').text('Edit Resource');
        $('.resource_label').html('Edit Resource: <span class="text-danger">*</span>');
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
                    required: "Please select Resource image",
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
        var dialog_title = (active == 1 ? "Are you sure you want to active Resource?" : "Are you sure you want to deactive Resource?");

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



    $(document).on('click','.resource_deleted', function() {
        var id= $(this).data('id');
        var deleted= $(this).data('inuse');
        
        var dialog_title = 'Are you sure want to delete this resource ?';
        var resourceTable_row = $(this).closest("tr");
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
                    url: 'resource/'+id,
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
                                resourceTable.row(resourceTable_row).remove().draw(false);
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

    var resourceId = $('.resource_id').val();
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

        $("#resource_form").validate({
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
                // short_description: {
                //     required: true,
                //     minlength: 20,
                //     maxlength: 350,
                // },
                "topic[]" :{
                    required: true,
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
                    required: "Please enter resource Title",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                // short_description: {
                //     required: "Please enter short description",
                //     minlength: jQuery.validator.format("At least {0} characters required"),
                //     maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                // },
                "topic[]" : {
                    required: "Please select Topic/Subtopic"
                },
                description: {
                    required: "Please enter Description",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                src: {
                    required: "Please select resource image",
                    extension: "Accepted file formats: jpg, jpeg, png",
                    filesize: "File size must be less than 1 MB",
                },
            },
            submitHandler: function (form) {
                $('.add-resource').attr("disabled", true);
                form.submit();
            }
        });
    });

    $(document).on('change','.custom_resource_upload',function(){
        $(this).find('.title-error-msg').children().hide();
    });

    $(document).on('click','.fileinput-remove',function(){
        $('.custom_resource_upload').find('.title-error-msg').children().show();
    });

    $(document).on('change','#is_upload_via_url', function(){
        $('.via_url').hide();
        $('.via_manual').show();
        if($(this).prop("checked") == true){
            $('.via_url').show();
            $('.via_manual').hide();
        }
    });
});
   </script>
@endsection
