<div id="modal_fee" class="modal" style="width:70%;height: 350px !important;">
	<form class="formValidate" id="fee_formValidate" method="post" action="{{ url('membership_update') }}">
	@csrf
	</br>
	<div class="modal-content">
		<h4>Edit Fee</h4>
		</br>
		<div class="row">
			<div class="col s12 m6">
				<label for="edit_fee_name">Fee name* </label>
				<select name="edit_fee_name" id="edit_fee_name" class="browser-default valid" aria-invalid="false">
					<option value="">Select</option>
				</select>
				<div class="input-field">
					<div class="errorTxt50"></div>
				</div>  
				<input id="edit_fee_auto_id" name="edit_fee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_fee_row_id" name="edit_fee_row_id" class='hide' value=""  type="text" >
			</div>
			<div class="input-field col s12 m6">
				
				<input id="edit_fee_amount" name="edit_fee_amount" class="" value=" "  type="text">
				<label for="edit_fee_amount">Fee amount *</label>
			</div>
			<div class="clearfix"> </div>
			
		</div>
	</div>
	<div class="modal-footer">
		<div class="col s12 m12">
			<button class="btn waves-effect waves-light purple right submit" id="update_fee" type="submit" name="update_fee">Update Fee</button>
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn left ">Close</a> 
		</div>
	</div>
	</form>
</div>
<div id="modal_nominee" class="modal" style="width:70%;height: 700px !important;">
	<form class="formValidate" id="nominee_formValidate" method="post" action="{{ url('membership_update') }}">
	@csrf
	<div class="modal-content">
		<h4>Edit Nominee</h4>
		<div class="row">
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_auto_id" name="edit_nominee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_nominee_row_id" name="edit_nominee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_nominee_name" name="edit_nominee_name" value=" "  type="text" >
				<input id="edit_nominee_id" name="edit_nominee_id" class='hide' value=" "  type="text" >
				<label for="edit_nominee_name">Nominee name* </label>
			</div>
			<div class="col s12 m4 row">
				<div class=" col s12 m8">
					<label for="edit_nominee_dob">DOB *</label>
					<input id="edit_nominee_dob" name="edit_nominee_dob" data-reflectage="edit_nominee_age" class="datepicker" value=" "  type="text">
				</div>
				<div class="col s12 m4">
					<label for="edit_nominee_age">Age</label>
					<span> 
					<input type="text" id="edit_nominee_age" readonly>
					</span>
				</div>
			</div>
			
			<div class="col s12 m4">
				<label for="edit_sex">Sex *</label>
				<select name="edit_sex" id="edit_sex" class="error browser-default">
					<option value="">Select</option>
					<option value="male" >Male</option>
					<option value="female" >Female</option>
				</select>
				<div class="input-field">
						<div class="errorTxt50"></div>
				</div>  
			</div>
			<div class="clearfix"> </div>
			<div class="col s12 m4">
				<label>Relationship*</label>
				<select name="edit_relationship" id="edit_relationship" data-error=".errorTxt31"  class="error browser-default">
					@foreach($data['relationship_view'] as $key=>$value)
						<option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}" >{{$value->relation_name}}</option>
					@endforeach
				</select>
					
				<div class="input-field">
					<div class="errorTxt31"></div>
				</div>   
			</div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nric_n" name="edit_nric_n" value=" "  type="text">
				<label for="edit_nric_n">NRIC-N *</label>
			</div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nric_o" name="edit_nric_o" value=" "  type="text">
				<label for="edit_nric_o">NRIC-O *</label>
			</div>
			<div class="clearfix"> </div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_address_one" name="edit_nominee_address_one" type="text" value=" " >
				<label for="edit_nominee_address_one">Address Line 1*</label>   
			</div>
			<div class="col s12 m4">
				<label>Country Name*</label>
				<select name="edit_nominee_country_id" id="edit_nominee_country_id"  class="error browser-default">
					<option value="">Select Country</option>
					@foreach($data['country_view'] as $value)
						<option value="{{$value->id}}" >{{$value->country_name}}</option>
					@endforeach
				</select>
				<div class="input-field">
					<div class="errorTxt35"></div>
				</div>       
				
			</div>
			<div class="col s12 m4">
					<label>State Name*</label>
				<select name="edit_nominee_state_id" id="edit_nominee_state_id"  class="error browser-default">
					<option value="">Select</option>
				</select>
				<div class="input-field">
						<div class="errorTxt36"></div>
				</div>       
				
			</div>
			<div class="clearfix"> </div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_address_two" name="edit_nominee_address_two" type="text" value=" " >
				<label for="edit_nominee_address_two">Address Line 2*</label>  
			</div>
			<div class="col s12 m4">
					<label>City Name*</label>
				<select name="edit_nominee_city_id" id="edit_nominee_city_id"  class="error browser-default">
					<option value="">Select</option>
				</select>
				<div class="input-field">
						<div class="errorTxt36"></div>
				</div>       
				
			</div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_postal_code" name="edit_nominee_postal_code" type="text" value=" " >
				<label for="edit_nominee_postal_code">Postal code*</label>    
			</div>
			<div class="clearfix"> </div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_address_three" name="edit_nominee_address_three" type="text" value=" " >
				<label for="edit_nominee_address_three">Address Line 3*</label>    
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_mobile" name="edit_nominee_mobile" type="text" value=" " >
				<label for="edit_nominee_mobile" class="active">Mobile No*</label>   
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_phone" name="edit_nominee_phone" type="text" value=" " >
				<label for="edit_nominee_phone" class="active">Phone No</label>    
			</div>
			<div class="clearfix"> </div>
			
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn waves-effect waves-light purple right submit" id="update_nominee" type="submit" name="update_nominee">Update Nominee<i class="material-icons right">send</i></button>
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn left ">Close</a> 
	</div>
	</form>
</div>
<script>
$('.modal').modal();
$("#membership_sidebar_a_id").addClass('active');
$('#country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#state_id").empty();
				//console.log('hi test');
				$.each(res,function(key,entry){
					$("#state_id").append($('<option></option>').attr('value', '').text("Select State"));
					$("#state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
			   // $('#state').material_select();
			}else{
			  $("#state_id").empty();
			}
			console.log(res);
		}
		});
	}else{
		$("#state_id").empty();
		$("#city_id").empty();
	}      
});
$('#state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			console.log(res);
			if(res)
			{
				$('#city_id').empty();
				$("#city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#city_id').empty();
			}
		   // console.log(res);
		}
	 });
   }else{
	   $('#city_id').empty();
   }
});
$('#company').change(function(){
   var CompanyID = $(this).val();
  
   if(CompanyID!='' && CompanyID!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-branch-list') }}?company_id="+CompanyID,
		success:function(res){
			//console.log(res);
			if(res)
			{
				$('#branch').empty();
				
				$.each(res,function(key,entry){
					$('#branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
				});
			}else{
				$('#branch').empty();
			}
			console.log(res);
		}
	 });
   }else{
	   $('#branch').empty();
   }
});
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd'
});
$('#add_fee').click(function(){
	var fee_row_id = parseInt($("#fee_row_id").val())+1;
	var member_auto_id =   $("#auto_id").val();
	var new_fee_id =   $("#new_fee_id").val();
	var selected = $("#new_fee_id").find('option:selected');
	var new_fee_name = selected.data('feename'); 
	var fee_amount =   $("#fee_amount").val();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	if(new_fee_id!="" && fee_amount!="" && fee_amount!="0" ){
		
		$("#add_fee").attr('disabled',true);
		var row_id =1;
		var new_row = '<tr>';
		new_row += '<td><span id="fee_name_label_'+fee_row_id+'">'+new_fee_name+'</span><input type="text" class="hide" name="fee_auto_id[]" id="fee_auto_id_'+fee_row_id+'"></input><input type="text" name="fee_name_id[]" class="hide" id="fee_name_id_'+fee_row_id+'" value="'+new_fee_id+'"></input></td>';
		new_row += '<td><span id="fee_amount_label_'+fee_row_id+'">'+fee_amount+'</span><input type="text" name="fee_name_amount[]" class="hide" id="fee_name_amount_'+fee_row_id+'" value="'+fee_amount+'"></input></td>';
		new_row += '<td><a class="btn-small waves-effect waves-light cyan edit_fee_row " href="#modal_nominee" data-id="'+fee_row_id+'">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4 delete_fee" data-id="'+fee_row_id+'" >Delete</a></td>';
		new_row += '</tr>';
		$("#fee_amount").val('');
		//$('#test3').find('input:text').val('');    
		$('#fee_table').append(new_row);
		$("#add_fee").attr('disabled',false);
		$("#fee_row_id").val(fee_row_id)
	}
	else{
		$("#add_fee").attr('disabled',false);
		M.toast({
			html: "Please choose fees and fill amount"
		});
	}    
});

$("#fee_formValidate").submit(function(e){
	e.preventDefault();
});
$("#fee_formValidate").validate({
	rules: {
		edit_fee_name: {
			required: true,
		},
		edit_fee_amount: {
			required: true,
		},
	},
	//For custom messages
	messages: {
		edit_fee_amount: {
			required: "Enter a Fee Amount",
		},
	},
	errorElement: 'div',
	errorPlacement: function (error, element) {
		var placement = $(element).data('error');
		if (placement) {
			$(placement).append(error)
		} else {
			error.insertAfter(element);
		}
	},
	submitHandler: function(form) {
		var row_id = $("#edit_fee_row_id").val();
		var edit_fee_auto_id = $("#edit_fee_name").val();
		var edit_fee_amount = $("#edit_fee_amount").val();
		var selected = $("#edit_fee_name").find('option:selected');
		var new_fee_name = selected.data('feename');
		//var formData = $("#fee_formValidate").serialize();
		$("#fee_name_id_"+row_id).val(edit_fee_auto_id);
		$("#fee_name_amount_"+row_id).val(edit_fee_amount);
		$("#fee_name_label_"+row_id).html(new_fee_name);
		$("#fee_amount_label_"+row_id).html(edit_fee_amount);
		$('#modal_fee').modal('close'); 
		return false;
	}
});

$('#new_fee_id').change(function(){
	var selected = $(this).find('option:selected');
	var feeamount = selected.data('feeamount'); 
	$("#fee_amount").val(feeamount);
});
$('#edit_fee_name').change(function(){
	var selected = $(this).find('option:selected');
	var feeamount = selected.data('feeamount'); 
	$("#edit_fee_amount").val(feeamount);
});
$(document.body).on('click', '.edit_fee_row' ,function(){
	var fee_id = $(this).data('id');
	$('#modal_fee').modal('open'); 
	var db_row_id = $('#fee_auto_id_'+fee_id).val(); 
	var fee_name_id = $('#fee_name_id_'+fee_id).val(); 
	
	//if(db_row_id==""){
		$('#edit_fee_auto_id').val(db_row_id); 
		var edit_fee_id = $('#fee_name_id_'+fee_id).val(); 
		var edit_fee_amount = $('#fee_name_amount_'+fee_id).val(); 
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/get-fee-options') }}",
			success:function(res){
				console.log(res);
				if(res)
				{
					$('#edit_fee_name').empty();
					$("#edit_fee_name").append($('<option></option>').attr('value', '').text("Select Fee"));
					$.each(res,function(key,entry){
						var selectval = edit_fee_id==entry.id ? 'selected' : '';
						$('#edit_fee_name').append($('<option '+selectval+' data-feeamount="'+entry.fee_amount+'" data-feename="'+entry.fee_name+'"></option>').attr('value',entry.id).text(entry.fee_name));
					});
				}else{
					$('#edit_fee_name').empty();
				}
				// console.log(res);
			}
		});
		$('#edit_fee_amount').val(edit_fee_amount);
		$('#edit_fee_row_id').val(fee_id);
	//}
});
$(document.body).on('click', '.delete_fee' ,function(){
	if(confirm('Are you sure you want to delete?')){
		var fee_id = $(this).data('id');
		var parrent = $(this).parents("tr");
		parrent.remove(); 
	}else{
		return false;
	}
	
});
$('#nominee_dob, #gaurdian_dob, #dob, #edit_nominee_dob').change(function(){
   var Dob = $(this).val();
   var reflect_age = $(this).data('reflectage'); 
   if(Dob!=""){
		$.ajax({
			type:"GET",
			dataType:"json",
			url:"{{URL::to('/get-age') }}? dob="+Dob,
			success:function(res){
				if(res){
					$("#"+reflect_age).val(res);
				}else{
					$("#"+reflect_age).val(0);
				}
			}
		});
   }else{
	  $("#"+reflect_age).val(0);
   }
	
});

 $('#nominee_country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#nominee_state_id").empty();
				//console.log('hi test');
				$("#nominee_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#nominee_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				$('#nominee_state_id').trigger('change');
			   // $('#state').material_select();
			}else{
			  $("#nominee_state_id").empty();
			}
			console.log(res);
		}
		});
	}else{
		$("#nominee_state_id").empty();
		$("#nominee_city_id").empty();
	}      
});
$('#nominee_state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			console.log(res);
			if(res)
			{
				$('#nominee_city_id').empty();
				$("#nominee_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#nominee_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#nominee_city_id').empty();
			}
		   // console.log(res);
		}
	 });
   }else{
	   $('#nominee_city_id').empty();
   }
});
 $('#add_nominee').click(function(){
	var nominee_row_id = parseInt($("#nominee_row_id").val())+1;
	alert(nominee_row_id);
	var auto_id =   $("#auto_id").val();
	var nominee_name =   $("#nominee_name").val();
	var nominee_dob =   $("#nominee_dob").val();
	var nominee_sex =   $("#sex").val();
	var nominee_relationship =   $("#relationship").val();
	var nric_n =   $("#nric_n").val();
	var nric_o =   $("#nric_o").val();
	var nominee_address_one =   $("#nominee_address_one").val();
	var nominee_country_id =   $("#nominee_country_id").val();
	var nominee_state_id =   $("#nominee_state_id").val();
	var nominee_address_two =   $("#nominee_address_two").val();
	var nominee_city_id =   $("#nominee_city_id").val();
	var nominee_postal_code =   $("#nominee_postal_code").val();
	var nominee_address_three =   $("#nominee_address_three").val();
	var nominee_mobile =   $("#nominee_mobile").val();
	var nominee_phone =   $("#nominee_phone").val();
	var nominee_age =   $("#nominee_age").val();
	
	var selected = $("#relationship").find('option:selected');
	var relationshipname = selected.data('relationshipname'); 
	$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	if(nominee_name!="" && nominee_dob!="" && nominee_sex!="" && nominee_relationship!="" && 
	nric_n!="" && nric_o!="" && nominee_address_one!="" && nominee_country_id!="" && nominee_state_id!="" && 
	nominee_address_two!="" && nominee_city_id!="" && nominee_postal_code != "" && nominee_address_three!="" && nominee_mobile!=""){
	   $("#add_nominee").attr('disabled',true);
	    var new_row = '<tr>';
		new_row += '<td><span id="nominee_name_label_'+nominee_row_id+'">'+nominee_name+'</span><input type="text" name="nominee_auto_id[]" class="hide" id="nominee_auto_id_'+nominee_row_id+'"></input><input class="hide" type="text" name="nominee_name_value[]" id="nominee_name_value_'+nominee_row_id+'" value="'+nominee_name+'"></input></td>';
		new_row += '<td><span id="nominee_age_label_'+nominee_row_id+'">'+nominee_age+'</span><input type="text" class="hide" name="nominee_age_value[]" id="nominee_age_value_'+nominee_row_id+'" value="'+nominee_age+'"></input><input type="text" class="hide" name="nominee_dob_value[]" id="nominee_dob_value_'+nominee_row_id+'" value="'+nominee_dob+'"></input></td>';
		new_row += '<td><span id="nominee_gender_label_'+nominee_row_id+'">'+nominee_sex+'</span><input class="hide" type="text" name="nominee_gender_value[]" id="nominee_gender_value_'+nominee_row_id+'" value="'+nominee_sex+'"></input></td>';
		new_row += '<td><span id="nominee_relation_label_'+nominee_row_id+'">'+relationshipname+'</span><input type="text" class="hide" name="nominee_relation_value[]" id="nominee_relation_value_'+nominee_row_id+'" value="'+nominee_relationship+'"></input></td>';
		new_row += '<td><span id="nominee_nricn_label_'+nominee_row_id+'">'+nric_n+'</span><input class="hide" type="text" name="nominee_nricn_value[]" id="nominee_nricn_value_'+nominee_row_id+'" value="'+nric_n+'"></input></td>';
		new_row += '<td><span id="nominee_nrico_label_'+nominee_row_id+'">'+nric_o+'</span><input type="text" class="hide" name="nominee_nrico_value[]" id="nominee_nrico_value_'+nominee_row_id+'" value="'+nric_o+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addressone_label_'+nominee_row_id+'">'+nominee_address_one+'</span><input type="text" class="hide" name="nominee_addressone_value[]" id="nominee_addressone_value_'+nominee_row_id+'" value="'+nominee_address_one+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addresstwo_label_'+nominee_row_id+'">'+nominee_address_two+'</span><input type="text" class="hide" name="nominee_addresstwo_value[]" id="nominee_addresstwo_value_'+nominee_row_id+'" value="'+nominee_address_two+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addressthree_label_'+nominee_row_id+'">'+nominee_address_three+'</span><input type="text" class="hide" name="nominee_addressthree_value[]" id="nominee_addressthree_value_'+nominee_row_id+'" value="'+nominee_address_three+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_country_label_'+nominee_row_id+'">'+nominee_country_id+'</span><input type="text" name="nominee_country_value[]" id="nominee_country_value_'+nominee_row_id+'" value="'+nominee_country_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_state_label_'+nominee_row_id+'">'+nominee_state_id+'</span><input type="text" name="nominee_state_value[]" id="nominee_state_value_'+nominee_row_id+'" value="'+nominee_state_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_city_label_'+nominee_row_id+'">'+nominee_city_id+'</span><input type="text" name="nominee_city_value[]" id="nominee_city_value_'+nominee_row_id+'" value="'+nominee_city_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_postalcode_label_'+nominee_row_id+'">'+nominee_postal_code+'</span><input type="text" name="nominee_postalcode_value[]" id="nominee_postalcode_value_'+nominee_row_id+'" value="'+nominee_postal_code+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_mobile_label_'+nominee_row_id+'">'+nominee_mobile+'</span><input type="text" name="nominee_mobile_value[]" id="nominee_mobile_value_'+nominee_row_id+'" value="'+nominee_mobile+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_phone_label_'+nominee_row_id+'">'+nominee_phone+'</span><input type="text" name="nominee_phone_value[]" id="nominee_phone_value_'+nominee_row_id+'" value="'+nominee_phone+'"></input></td>';
		new_row += '<td><a class="btn-small waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="'+nominee_row_id+'">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4 delete_nominee" data-id="'+nominee_row_id+'" >Delete</a></td>';
		new_row += '</tr>';
		//$('#test2').find('input:text').val('');    
		$('#nominee_add_section').find('input:text').val('');  
		$('#nominee_table').append(new_row);
		M.toast({
			html: 'Nominee added successfully'
		});
		$("#add_nominee").attr('disabled',false);
		$("#nominee_row_id").val(nominee_row_id);
		/* $.ajax({
			method: 'POST', // Type of response and matches what we said in the route
			url: "{{ URL::to('/add-nominee') }}", // This is the url we gave in the route
			data: { 
				'auto_id' : auto_id,
				'nominee_name' : nominee_name,
				'nominee_dob' : nominee_dob,
				'nominee_sex' : nominee_sex,
				'nominee_relationship' : nominee_relationship,
				'nric_n' : nric_n,
				'nric_o' : nric_o,
				'nominee_address_one' : nominee_address_one,
				'nominee_country_id' : nominee_country_id,
				'nominee_state_id' : nominee_state_id,
				'nominee_address_two' : nominee_address_two,
				'nominee_city_id' : nominee_city_id,
				'nominee_postal_code' : nominee_postal_code,
				'nominee_address_three' : nominee_address_three,
				'nominee_mobile' : nominee_mobile,
				'nominee_phone' : nominee_phone,
			}, // a JSON object to send back
			dataType: "json",
			success: function(response){ // What to do if we succeed
				$("#add_nominee").attr('disabled',false);
				var alert_confirm = "confirm('Are you sure you want to delete?')";
				console.log(response.data); 
				if(response.status ==1){
					
				}
				console.log(response.data); 
			},
			error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
				console.log(JSON.stringify(jqXHR));
				console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
			}
		}); */
	}
	else{
		$("#add_nominee").attr('disabled',false);
		M.toast({
			html: "Please fill requierd fields"
		});
	}    
});

$('#edit_nominee_state_id').change(function(e, data){
   var StateId = $(this).val();
	console.log(StateId);
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			console.log(res);
			if(res)
			{
				$('#edit_nominee_city_id').empty();
				$("#edit_nominee_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#edit_nominee_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
				if(typeof data !='undefined'){
					$('#edit_nominee_city_id').val(data.city_id);
				}
				
			}else{
				$('#edit_nominee_city_id').empty();
			}
		   // console.log(res);
		}
	 });
   }else{
	   $('#edit_nominee_city_id').empty();
   }
});


 $('#edit_nominee_country_id').change(function(e, data){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#edit_nominee_state_id").empty();
				//console.log('hi test');
				$("#edit_nominee_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#edit_nominee_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				if(typeof data !='undefined'){
					$('#edit_nominee_state_id').val(data.state_id);
					$('#edit_nominee_state_id').trigger('change', [{state_id: data.state_id, city_id: data.city_id}]);
				}
			   // $('#state').material_select();
			}else{
			  $("#edit_nominee_state_id").empty();
			}
			//console.log(res);
		}
		});
	}else{
		$("#edit_nominee_state_id").empty();
		$("#edit_nominee_city_id").empty();
	}      
});



$(document.body).on('click', '.edit_nominee_row' ,function(){
	var nominee_id = $(this).data('id');
	$('#modal_nominee').modal('open'); 
	var db_row_id = $('#nominee_auto_id_'+nominee_id).val(); 
	var nominee_age = $('#nominee_age_value_'+nominee_id).val(); 
	var nominee_dob = $('#nominee_dob_value_'+nominee_id).val(); 
	var nominee_name = $('#nominee_name_value_'+nominee_id).val(); 
	var nominee_gender = $('#nominee_gender_value_'+nominee_id).val(); 
	var nominee_relation = $('#nominee_relation_value_'+nominee_id).val(); 
	var nominee_nricn = $('#nominee_nricn_value_'+nominee_id).val(); 
	var nominee_nrico = $('#nominee_nrico_value_'+nominee_id).val(); 
	var nominee_addressone = $('#nominee_addressone_value_'+nominee_id).val(); 
	var nominee_addresstwo = $('#nominee_addresstwo_value_'+nominee_id).val(); 
	var nominee_addressthree = $('#nominee_addressthree_value_'+nominee_id).val(); 
	var nominee_country = $('#nominee_country_value_'+nominee_id).val(); 
	var nominee_state = $('#nominee_state_value_'+nominee_id).val(); 
	var nominee_city = $('#nominee_city_value_'+nominee_id).val(); 
	var nominee_postal_code = $('#nominee_postalcode_value_'+nominee_id).val(); 
	var nominee_mobile = $('#nominee_mobile_value_'+nominee_id).val(); 
	var nominee_phone = $('#nominee_phone_value_'+nominee_id).val(); 
	$("#edit_nominee_auto_id").val( db_row_id );
	$("#edit_nominee_row_id").val( nominee_id );
	$("#edit_nominee_name").val( nominee_name );
	$("#edit_nominee_dob").val( nominee_dob );
	$("#edit_sex").val( nominee_gender );
	$("#edit_relationship").val( nominee_relation );
	$("#edit_nric_n").val( nominee_nricn );
	$("#edit_nric_o").val( nominee_nrico );
	$("#edit_nominee_address_one").val( nominee_addressone );
	$("#edit_nominee_country_id").val( nominee_country );
	$("#edit_nominee_country_id").trigger('change',[{country_id: nominee_country, state_id: nominee_state, city_id: nominee_city}]);
	$("#edit_nominee_state_id").val( nominee_state );
	$("#edit_nominee_address_two").val( nominee_addresstwo );
	$("#edit_nominee_city_id").val( nominee_city );
	$("#edit_nominee_postal_code").val( nominee_postal_code );
	$("#edit_nominee_address_three").val( nominee_addressthree );
	$("#edit_nominee_mobile").val( nominee_mobile );
	$("#edit_nominee_phone").val( nominee_phone );
	$("#edit_nominee_age").val( nominee_age );
	/* $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-nominee-data') }}?nominee_id="+nominee_id,
		success:function(res){
			console.log(res);
			if(res)
			{
				$("#edit_nominee_id").val(res.id);
				$("#edit_nominee_name").val(res.nominee_name);
				$("#edit_nominee_dob").val(res.dob);
				$("#edit_sex").val(res.gender);
				$("#edit_relationship").val(res.relation_id);
				$("#edit_nric_n").val(res.nric_n);
				$("#edit_nric_o").val(res.nric_o);
				$("#edit_nominee_address_one").val(res.address_one);
				$("#edit_nominee_country_id").val(res.country_id);
				$("#edit_nominee_state_id").val(res.state_id);
				$("#edit_nominee_address_two").val(res.address_two);
				$("#edit_nominee_city_id").val(res.city_id);
				$("#edit_nominee_postal_code").val(res.postal_code);
				$("#edit_nominee_address_three").val(res.address_three);
				$("#edit_nominee_mobile").val(res.mobile);
				$("#edit_nominee_phone").val(res.phone);
				console.log(res.dob);
				$('#modal_nominee').modal('open'); 
			}else{
				
			}
		   // console.log(res);
		}
	 }); */
});
 $("#nominee_formValidate").validate({
	rules: {
		edit_nominee_name: {
			required: true,
		},
		edit_nominee_dob: {
			required: true,
		},
		edit_sex: {
			required: true,
		},
		edit_relationship: {
			required: true,
		},
		edit_nric_n: {
			required: true,
		},
		edit_nric_o: {
			required: true,
		},
		edit_nominee_address_one: {
			required: true,
		},
		edit_nominee_country_id: {
			required: true,
		},
		edit_nominee_state_id: {
			required: true,
		},
		edit_nominee_address_two: {
			required: true,
		},
		edit_nominee_city_id: {
			required: true,
		},
		edit_nominee_postal_code: {
			required: true,
		},
		edit_nominee_address_three: {
			required: true,
		},
		edit_nominee_mobile: {
			required: true,
		},
	},
	//For custom messages
	messages: {
		edit_nominee_name: {
			required: "Enter a Nominee name",
		},
	},
	errorElement: 'div',
	errorPlacement: function (error, element) {
		var placement = $(element).data('error');
		if (placement) {
			$(placement).append(error)
		} else {
			error.insertAfter(element);
		}
	},
	submitHandler: function(form) {
		var formData = $("#nominee_formValidate").serialize();
		var row_id = $("#edit_nominee_row_id").val();
		var nominee_name = $("#edit_nominee_name").val();
		var nominee_dob = $("#edit_nominee_dob").val();
		var nominee_age = $("#edit_nominee_age").val();
		var sex = $("#edit_sex").val();
		var relationship = $("#edit_relationship").val();
		var nric_n = $("#edit_nric_n").val();
		var nric_o = $("#edit_nric_o").val();
		var nominee_address_one = $("#edit_nominee_address_one").val();
		var nominee_address_two = $("#edit_nominee_address_two").val();
		var nominee_address_three = $("#edit_nominee_address_three").val();
		var country_id = $("#edit_nominee_country_id").val();
		var state_id = $("#edit_nominee_state_id").val();
		var city_id = $("#edit_nominee_city_id").val();
		var postal_code = $("#edit_nominee_postal_code").val();
		var mobile = $("#edit_nominee_mobile").val();
		var phone = $("#edit_nominee_phone").val();
		var selected = $("#edit_relationship").find('option:selected');
		var relationshipname = selected.data('relationshipname');
		//var formData = $("#fee_formValidate").serialize();
		
		$("#nominee_name_label_"+row_id).html(nominee_name);
		$("#nominee_name_value_"+row_id).val(nominee_name);
		$("#nominee_age_label_"+row_id).html(nominee_age);
		$("#nominee_age_value_"+row_id).val(nominee_age);
		$("#nominee_dob_value_"+row_id).val(nominee_dob);
		$("#nominee_gender_value_"+row_id).val(sex);
		$("#nominee_gender_label_"+row_id).html(sex);
		$("#nominee_relation_label_"+row_id).html(relationshipname);
		$("#nominee_relation_value_"+row_id).val(relationship);
		$("#nominee_nricn_value_"+row_id).val(nric_n);
		$("#nominee_nricn_label_"+row_id).html(nric_n);
		$("#nominee_nrico_label_"+row_id).html(nric_o);
		$("#nominee_nrico_value_"+row_id).val(nric_o);
		$("#nominee_addressone_value_"+row_id).val(nominee_address_one);
		$("#nominee_addresstwo_value_"+row_id).val(nominee_address_two);
		$("#nominee_addressthree_value_"+row_id).val(nominee_address_three);
		$("#nominee_country_value_"+row_id).val(country_id);
		$("#nominee_state_value_"+row_id).val(state_id);
		$("#nominee_city_value_"+row_id).val(city_id);
		$("#nominee_postalcode_value_"+row_id).val(postal_code);
		$("#nominee_mobile_value_"+row_id).val(mobile);
		$("#nominee_phone_value_"+row_id).val(phone);
		$('#modal_nominee').modal('close'); 
		/* $.ajax({
			method: 'POST', // Type of response and matches what we said in the route
			url: "{{ URL::to('/update-nominee') }}", // This is the url we gave in the route
			data: formData, // a JSON object to send back
			dataType: "json",
			success: function(response){ // What to do if we succeed
				$("#update_nominee").attr('disabled',false);
				
				console.log(response.data); 
				if(response.status ==1){
					var row_id = response.data.nominee_id;
					$("#nominee_name_"+row_id).html(response.data.name);
					$("#nominee_age_"+row_id).html(response.data.age);
					$("#nominee_gender_"+row_id).html(response.data.gender);
					$("#nominee_relation_"+row_id).html(response.data.relationship);
					$("#nominee_nricn_"+row_id).html(response.data.nric_n);
					$("#nominee_nrico_"+row_id).html(response.data.nric_o);
					$('#modal_nominee').modal('close'); 
				}
			   
				console.log(response.data); 
			},
			error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
				console.log(JSON.stringify(jqXHR));
				console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
			}
		}); */
	}
});
$(document.body).on('click', '.delete_nominee' ,function(){
	if(confirm('Are you sure you want to delete?')){
		var fee_id = $(this).data('id');
		var parrent = $(this).parents("tr");
		parrent.remove(); 
	}else{
		return false;
	}
	
});

$('#guardian_country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#guardian_state_id").empty();
				//console.log('hi test');
				$("#guardian_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#guardian_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				$('#guardian_state_id').trigger('change');
			   // $('#state').material_select();
			}else{
			  $("#guardian_state_id").empty();
			}
			console.log(res);
		}
		});
	}else{
		$("#guardian_state_id").empty();
		$("#guardian_city_id").empty();
	}      
});
$('#guardian_state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			console.log(res);
			if(res)
			{
				$('#guardian_city_id').empty();
				$("#guardian_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#guardian_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#guardian_city_id').empty();
			}
		   // console.log(res);
		}
	 });
   }else{
	   $('#guardian_city_id').empty();
   }
});
</script>