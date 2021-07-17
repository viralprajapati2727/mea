@extends('admin.app-admin')
@section('title') Email Subscriptions @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.email-subscriptions') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Email Subscription</a>
                <span class="breadcrumb-item active">Email Subscription</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Email Subscription</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_resource"><i class="icon-plus2"></i> Send Email</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <div class="event-search-icon">
                        <i class="flaticon-search"></i>
                    </div>
                    <label class="text-secondary">Search</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-4 padding-top-class pt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state userlListDatabase" id="datatable">
        <thead>
            <tr>
                <th>Email</th>
            </tr>
        </thead>
    </table>
</div>
<div id="add_modal" class="add_modal modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title blog_title">Send Email</h5>
                <button type="button" class="close custom_close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('admin.send-subscription-mail') }}" method="POST" id="blog_form">
                @csrf
                <div class="modal-body">
                    {{-- <div class="form-group row">
                        <label class="col-form-label pl-2">Title :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="title" placeholder="Enter Title" class="form-control dance_music_type "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div> --}}
                    {{-- <div class="form-group row">
                        <label class="col-form-label pl-2">Short Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="short_description" id="short_description" rows="5" placeholder="Enter Short Description" class="form-control short_description"></textarea>
                        </div>
                        <div class="input-group short_description-error-msg"></div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-form-label pl-2">E Mail Body:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="description" id="description" rows="5" placeholder="Enter Description" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
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
    var userTable = "";
    var filter = "{{ route('admin.email-subscriptions-filter') }}";
    
$(document).ready(function () {
    userTable = $('#datatable').DataTable({
        serverSide: true,
        bFilter: false,
        ajax: {
            url: filter,
            type: 'POST',
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
            data: function (d) {
                d.keyword = $('#keyword').val();
            },
            complete: function () {
                $('body').unblock();
            },
        },
        columns: [
            { data: 'email', name: 'email', searchable: false },
            // { data: 'gender', name: 'gender', searchable: false },
            // { data: 'location', name: 'location', searchable: false },
            // { data: 'status', name: 'status', searchable: false },
            // { data: 'action', name: 'action', searchable: false, orderable: false }
        ]
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });


    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
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
            else if (element.parents('div').hasClass('ckeditor')) {
                error.appendTo( element.parent().parent());
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
                // title: {
                //     required: true,
                //     minlength: 2,
                //     maxlength: 100,
                // },
                description: {
                required: function(){
                    CKEDITOR.instances.description.updateElement();
                    var editorcontent = $('#description').val().replace(/<[^>]*>/gi, ''); // strip tags
                    var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
                    return Number(editor_value) === 0;
                    },
                    checkCkeditorEmpty: '#description',
                }
            },
            debug: true,
            messages: {
                description: {
                    required: "Please enter email body",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                }
            },
            submitHandler: function (form) {
                $('.add-blog').attr("disabled", true);
                form.submit();
            }
        });
});

</script>
@endsection
