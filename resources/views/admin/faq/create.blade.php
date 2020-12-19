@extends('admin.app-admin')
@section('title') FAQ @endsection
@section('page-header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('faq.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>FAQ</a>
                <span class="breadcrumb-item active">{{ isset($model) ? 'Update' : 'Add' }}</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body mx-auto">
        <div class="col-md-12">
            @if( isset($model) )
                {!! Form::model($model, ['route' => ['faq.update', $id], 'method' => 'PUT', 'class' => 'faq_form']) !!}
            @else
                {!! Form::open(['route' => 'faq.store', 'class' => 'faq_form']) !!}
            @endif
            @php $question = $answer = "";@endphp
                @if(isset($model) )
                    @php
                            $question = $model->question;
                            $answer = $model->answer;
                    @endphp
                @endif
                <div class="form-group row">
                    {!! Form::label('question', 'Question',['class' => 'col-lg-2 col-form-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::text('question', $question, ['class' => 'form-control']) !!}
                        @if ($errors->has('question'))
                            {!! Form::label('question', $errors->first('question'), ['class' => 'validation-error-label']) !!}
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('answer', 'Answer',['class' => 'col-lg-2 col-form-label']) !!}
                    <div class="col-lg-10 ckckeditor">
                        {!! Form::textarea('answer', $answer, ['class' => 'form-control ckeditor', 'id' => 'answer']) !!}
                        @if ($errors->has('answer'))
                            {!! Form::label('answer', $errors->first('answer'), ['class' => 'validation-error-label']) !!}
                        @endif
                    </div>
                    @if( isset($model) )
                        <input type="hidden" name="id" id="faq-id" value="{{ $model->id }}">
                    @endif
                </div>
                <div class="form-group row">
                {!! Form::label('title', 'Status', [ 'class' => 'control-label col-lg-2']) !!}
                    {{-- <div class="row my-2"> --}}
                        <div class="col-lg-10">
                        {{-- <div class="col-md-12"> --}}
                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="status" class="custom-control-input" id="custom_checkbox_stacked_unchecked" value="1" {{ isset($model) ? ($model->status == 1 ? 'checked' : '') : 'checked' }}>
                                <label class="custom-control-label" for="custom_checkbox_stacked_unchecked">Active</label>
                            </div>
                        {{-- </div> --}}
                        </div>
                    {{-- </div> --}}
                    @if ($errors->has('status'))
                        {!! Form::label('status', $errors->first('status'), ['class' => 'validation-error-label']) !!}
                    @endif
                </div>
                <div class="text-right form-group">
                    <button type="submit" class="btn btn-primary rounded-round">{{ isset($model) ? 'Update' : 'Add' }}</button>
                    <a href="{{ route('faq.index') }}"><button type="button" class="btn btn-danger rounded-round">Cancel</button></a>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    CKEDITOR.instances.answer.on('change', function () {
        CKEDITOR.instances.answer.updateElement();
        $(".faq_form").valid();
    });

    function GetTextFromHtml(html) {
        var dv = document.createElement("DIV");
        dv.innerHTML = html;
        return dv.textContent || dv.innerText || "";
    }

    $(".faq_form").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        // ignore: "input:hidden:not(input:hidden.required)",
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        },
        errorPlacement: function(error, element) {
                // Styled checkboxes, radios, bootstrap switch
                if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                    if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo( element.parent().parent().parent().parent() );
                    }
                    else {
                        error.appendTo( element.parent().parent().parent().parent().parent() );
                    }
                }
                // Unstyled checkboxes, radios
                else if (element.parents('div').hasClass('ckckeditor')) {
                    error.appendTo( element.parent() );
                }
                else {
                    error.insertAfter(element);
                }
            },
        rules: {
        question: {
            required: true,
            remote:{
                url: base_url + 'check-unique-faq-question',
                type:'POST',
                data:{id:function() { return $('#faq-id').val(); }},
            },
            normalizer: function (value) { return $.trim(value); },
        },
        answer: {
            required: function (textarea) {
                CKEDITOR.instances.answer.updateElement();
                var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                return editorcontent.length === 0;
            },
        },
        },
        messages: {
        question: {
            required: "Please enter question",
            remote: "The question has already been taken.",
        },
        answer: {
            required: "Please enter answer",
        },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.answer.updateElement();
        form.submit();
        }
    });
});
</script>
@endsection
