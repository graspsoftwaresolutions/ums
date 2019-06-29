/*
 * Form Validation
 */
$(function () {
  $("#formValidate").validate({
    rules: {
      uname: {
        required: true,
        minlength: 5
      },
      country_name: {
        required: true,
      },
	  state_name: {
        required: true,
      },
	  country_id: "required",
      cemail: {
        required: true,
        email: true
      },
	  city_name : {
		required: true,
	  },
	  designation_name : {
		required: true,
	  },
      password: {
        required: true,
        minlength: 5
      },
      cpassword: {
        required: true,
        minlength: 5,
        equalTo: "#password"
      },
      curl: {
        required: true,
        url: true
      },
      crole: "required",
      ccomment: {
        required: true,
        minlength: 15
      },
      tnc_select: "required",
    },
    //For custom messages
    messages: {
      uname: {
        required: "Enter a username",
        minlength: "Enter at least 5 characters"
      },
      country_name: {
         required: "Enter a Country Name",
      },
	  state_name: {
         required: "Enter a State Name",
      },
	  city_name: {
         required: "Enter a City Name",
      },
	  designation_name: {
         required: "Enter a Designation Name",
      },
      curl: "Enter your website",
    },
    errorElement: 'div',
    errorPlacement: function (error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    }
  });
});