
$(document).ready(function () { 
  	$.validator.addMethod("emailValidation", function (value, element) {
       return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,8}$/i.test(value);
   	});
  	$.validator.addMethod('customphone', function (value, element) {
    	console.log(element,"contact-us");
    return this.optional(element) || /^[+-]?\d+$/.test(value);
});
$(".contact-form").validate({
    ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
	debug: true,
	errorClass: 'error',
	highlight: function(element, errorClass) {
		$(element).removeClass(errorClass);
	},
	unhighlight: function(element, errorClass) {
		$(element).removeClass(errorClass);
	},
	// Different components require proper error label placement
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
		else {
			error.insertAfter(element);
		}
	},
    rules: {
       	name: {
         	required:true,
         	minlength:2,
         	maxlength:255,
          	normalizer: function (value) { return $.trim(value); },
       	},
       	email:{
         	required:true,
         	emailValidation:true,
         	maxlength:255,
       	},
       	subject:{
         	required:true,
         	maxlength:255,
       	},
        message:{
          	required:true,
          	minlength:2,
          	maxlength:1000,
          	normalizer: function (value) { return $.trim(value); },
        },
    },
    messages: {
        name:{
            required:"Please enter name",
            alpha:"Only letters, hyphens(-) and space are allowed in name name",
            minlength: jQuery.validator.format("At least {0} characters are required"),
            maxlength: jQuery.validator.format("Maximum {0} characters are allowed")
        },
        email:{
            required:"Please enter email",
            alpha:"Only letters, hyphens(-) and space are allowed in email",
            maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
            emailValidation:"Please enter valid email address"
        },
        subject:{
            required:"Please enter subject",
            maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
        },
        message:{
            required:"Please enter message",
            alpha:"Only letters, hyphens(-) and space are allowed in message",
            minlength: jQuery.validator.format("At least {0} characters are required"),
            maxlength: jQuery.validator.format("Maximum {0} characters are allowed")
        },
    },
    submitHandler: function(form) {
      	form.submit();
    }
   });
});