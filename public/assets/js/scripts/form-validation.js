/*
 * Form Validation
 */
$(function () {
  $("#formValidate").validate({
    rules: {
      member_title:{
        required: true,
      },
      member_number: {
        required: true,
      },
      name: {
        required: true,
      },
      gender: {
        required: true,
      },
      name: {
        required: true,
      },
      phone: {
        required: true,
      },
      email: {
        required: true,
      },
      doe: {
        required: true,
      },
      designation: {
        required: true,
      },
      race: {
        required: true,
      },
      country: {
        required: true,
      },
      state: {
        required: true,
      },
      city: {
        required: true,
      },
      postal_code: {
        required: true,
      },
      address_one: {
        required:true,
      },
      dob: {
          required:true,
      },
      new_ic: {
          required:true,
          minlength: 3,
          maxlength: 20,
      },
      salary: {
        required: true,
      },
      branch: {
        required: true,
      },
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
        member_title: {
          required: "Please Enter Your Title ",
         
      },
      member_number: {
          required: "Please Enter Member NUmber",
         
      },
      name: {
          required: "Please Enter Your Name",
          
      },
      gender: {
          required: "Please choose Gender",
      },
      phone: {
          required: "Please Enter your Number",
          
      },
      email: {
          required: "Please enter valid email",
          },
      designation: {
          required: "Please choose  your Designation",
      },
      race: {
          required: "Please Choose your Race ",
      },
      country: {
          required:"Please choose  your Country",
      },
      state: {
          required:"Please choose  your State",
      },
      city: {
          required:"Please choose  your city",
      },
      address_one: {
          required:"Please Enter your Address",
      },
      dob: {
          required:"Please choose DOB",
      },
      new_ic: {
          required:"Please Enter New Ic Number",
      },
      salary: {
        required:"Please Enter salary Name",
      },
      branch: {
          required:"Please Choose Company Name",
      },
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