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
<script>
$('.modal').modal();
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
	if(new_fee_id!="" && fee_amount!="" ){
		
		$("#add_fee").attr('disabled',true);
		var row_id =1;
		var new_row = '<tr>';
		new_row += '<td><span id="fee_name_label_'+fee_row_id+'">'+new_fee_name+'</span><input type="text" name="fee_auto_id[]" id="fee_auto_id_'+fee_row_id+'"></input><input type="text" name="fee_name_id[]" id="fee_name_id_'+fee_row_id+'" value="'+new_fee_id+'"></input></td>';
		new_row += '<td><span id="fee_amount_label_'+fee_row_id+'">'+fee_amount+'</span><input type="text" name="fee_name_amount[]" id="fee_name_amount_'+fee_row_id+'" value="'+fee_amount+'"></input></td>';
		new_row += '<td><a class="btn-small waves-effect waves-light cyan edit_fee_row " href="#modal_nominee" data-id="'+fee_row_id+'">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4 delete_fee" data-id="'+fee_row_id+'" >Delete</a></td>';
		new_row += '</tr>';
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
 $('#add_nominee').click(function(){
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
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        if(nominee_name!="" && nominee_dob!="" && nominee_sex!="" && nominee_relationship!="" && 
        nric_n!="" && nric_o!="" && nominee_address_one!="" && nominee_country_id!="" && nominee_state_id!="" && 
        nominee_address_two!="" && nominee_city_id!="" && nominee_postal_code != "" && nominee_address_three!="" && nominee_mobile!=""){
           $("#add_nominee").attr('disabled',true);
            $.ajax({
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
                        var new_row = '<tr>';
                        new_row += '<td id="nominee_name_'+ response.data.nominee_id +'">'+nominee_name+'</td>';
                        new_row += '<td id="nominee_age_'+ response.data.nominee_id +'">'+response.data.age+'</td>';
                        new_row += '<td id="nominee_gender_'+ response.data.nominee_id +'">'+nominee_sex+'</td>';
                        new_row += '<td id="nominee_relation_'+ response.data.nominee_id +'">'+response.data.relationship+'</td>';
                        new_row += '<td id="nominee_nricn_'+ response.data.nominee_id +'">'+nric_n+'</td>';
                        new_row += '<td id="nominee_nrico_'+ response.data.nominee_id +'">'+nric_o+'</td>';
                        new_row += '<td><a class="btn-small waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="'+response.data.nominee_id+'">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4 delete_nominee" data-id="'+response.data.nominee_id+'" onclick="if ('+alert_confirm+') return true; else return false;">Delete</a></td>';
                        new_row += '</tr>';
                        $('#test2').find('input:text').val('');    
                        $('#nominee_table').append(new_row);
                        M.toast({
                            html: response.message
                        });
                    }
                    console.log(response.data); 
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
        else{
            $("#add_nominee").attr('disabled',false);
            M.toast({
                html: "Please fill requierd fields"
            });
        }    
    });
</script>