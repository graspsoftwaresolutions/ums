<!-- BEGIN: Footer-->
@if(!empty(Auth::user()))
<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container"><span>&copy; <?php echo date('Y') ?> <a href="#" target="_blank">Membership</a> {{__('All rights reserved')}}.</span><span class="right hide-on-small-only"></span></div>
    </div>
</footer>
@endif
<!-- END: Footer-->
<!-- BEGIN VENDOR JS-->
<script src="{{ asset('public/assets/js/vendors.min.js') }}" type="text/javascript"></script>
<script>
var base_url = '{{ URL::to("/") }}';
/**
 * Handle loading overlays
 *
 * @author Justin Stolpe
 */
var loader = {
	/**
	 * Initialize our loading overlays for use
	 *
	 * @params void
	 *
	 * @return void
	 */
	initialize : function () {
		var load_image_path = '{{ asset('public/images/loading.gif') }}';
		var html = 
			'<div class="loading-overlay"></div>' +
			'<div class="loading-overlay-image-container">' +
				'<img src="'+load_image_path+'" class="loading-overlay-img"/>' +
			'</div>';

		// append our html to the DOM body
		$( 'body' ).append( html );
	},

	/**
	 * Show the loading overlay
	 *
	 * @params void
	 *
	 * @return void
	 */
	showLoader : function () {
		jQuery( '.loading-overlay' ).show();
		jQuery( '.loading-overlay-image-container' ).show();
	},

	/**
	 * Hide the loading overlay
	 *
	 * @params void
	 *
	 * @return void
	 */
	hideLoader : function () {
		jQuery( '.loading-overlay' ).hide();
		jQuery( '.loading-overlay-image-container' ).hide();
	}
}
</script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->

@section('footerSection')
@show
<!-- END PAGE VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="{{ asset('public/assets/js/plugins.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/custom/custom-script.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/customizer.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/ui-alerts.js') }}" type="text/javascript"></script>
<!-- END THEME  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<!--script src="{{ asset('public/assets/js/scripts/intro.js') }}" type="text/javascript"></script-->
<!-- END PAGE LEVEL JS-->
@php $logo = CommonHelper::getLogo(); 
  $logourl = asset('public/assets/images/logo/'.$logo);
@endphp
<script>
$(window).on("resize", function() {
   resizetable();
});

function resizetable() {
   if($(window).width() < 976){
      if($('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo').length > 0){
         $('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo img').attr('src','{{ $logourl }}');
      }
      if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length > 0){
         $('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo img').attr('src','{{ $logourl }}');
      }
      if($('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo').length > 0){
         $('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo img').attr('src','{{ $logourl }}');
      }
   }
   else{
      if($('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo').length > 0){
         $('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo img').attr('src','{{ $logourl }}');
      }
      if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length > 0){
         $('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo img').attr('src','{{ $logourl }}');
      }
      if($('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo').length > 0){
         $('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo img').attr('src','{{ $logourl }}');
      }
   }
}
resizetable();
</script>

@section('footerSecondSection')
@show
<script>
$(".card-alert").fadeTo(3000, 1000).slideUp(1000, function () {
    $(".card-alert").slideUp(1000);
});
</script>
<script>

$(document).ready(function(){
	 $('.selectpicker').select2({width: "100%"});
    //$('#country_id').select2({width: "100%"});
    //$('#state_id').select2({width: "100%"});
    //$('#city_id').select2({width: "100%"});
 });
 </script>
<link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet"/>
<script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
<style>
    .select2 .selection .select2-selection--single, .select2-container--default .select2-search--dropdown .select2-search__field {
        border-width: 0 0 1px 0 !important;
        border-radius: 0 !important;
        height: 2.80rem;
    }

    .select2-container--default .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-width: 0 0 1px 0 !important;
        border-radius: 0 !important;
    }

    .select2-results__option {
        color: #26a69a;
        padding: 8px 16px;
        font-size: 16px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eee !important;
        color: #26a69a !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e1e1e1 !important;
    }

    .select2-dropdown {
        border: none !important;
        box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
    }

    .select2-container--default .select2-results__option[role=group] .select2-results__group {
        background-color: #333333;
        color: #fff;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 0 !important;
    }

    .select2-container .select2-search--inline .select2-search__field:focus {
        border-bottom: none !important;
        box-shadow: none !important;
    }

    .select2-container .select2-selection--multiple {
        min-height: 2.05rem !important;
    }

    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #ddd !important;
        color: rgba(0,0,0,0.26);
        border-bottom: 1px dotted rgba(0,0,0,0.26);
    }

    input[type=text],
    input[type=password],
    input[type=email],
    input[type=url],
    input[type=time],
    input[type=date],
    input[type=datetime-local],
    input[type=tel],
    input[type=number],
    input[type=search],
    textarea.materialize-textarea {
        &.valid + label::after,
        &.invalid + label::after,
        &:focus.valid + label::after,
            &:focus.invalid + label::after {
            white-space: pre;
        }
        &.empty {
            &:not(:focus).valid + label::after,
                &:not(:focus).invalid + label::after {
                top: 2.8rem;

            } 
        }
    }
</style>
<script>
$('.selectpickermodal').select2({
   dropdownParent: $('#modal_add_edit')
});
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

$(document).ready(function() {
    loader.hideLoader();
    window.onload = function () {
        //loader.showLoader();
    }
});
$(".datepicker-normal,.datepicker,.datepicker-custom").attr("readonly",true);
</script>
