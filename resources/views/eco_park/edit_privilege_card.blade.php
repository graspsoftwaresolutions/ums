@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
@endsection
@section('main-content')
<div id="">
    <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section ">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        @if(isset($data['union_branch'])){
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Edit Privilege Card Details') }}
                                        </h5>
                                        @else
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Privilege Card Details')}}</h5>
                                        @endif
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Edit Privilege Card') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right"
                                            href="{{route('privilegecard.users', app()->getLocale())}}">{{__('Back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    @include('includes.messages')
                                    <h4 class="card-title">{{__('Edit Privilege Card') }}</h4>
                                    @php
                                        if(isset($data['card_view'])){
                                            $values = $data['card_view']; 
                                            //dd($values );
                                        }else{ 
                                            echo 'invalid access'; die; 
                                        }
                                    @endphp
                                    <div id="view-validations">
                                        <form class="formValidate" id="wizard2" method="post" enctype="multipart/form-data" action="{{ url(app()->getLocale().'/pc_user_save') }}">
                                            @csrf
                                            <input type="hidden" name="auto_id" id="auto_id"
                                                value="{{$values->pid}}">
                                             <div class="row">
                                                <div class="input-field col s12 m6 @if($values->member_number==0) hide @endif">
                                                    <label for="member_number" class="force-active">Member Number *</label>
                                                    <input id="member_number" name="member_number" value="{{$values->member_number}}" readonly type="text" data-error=".errorTxt29">
                                                    <div class="errorTxt29"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="full_name" class="force-active">Member Name *</label>
                                                    <input id="full_name" name="full_name" required="" value="{{ $values->full_name }}" type="text" data-error=".errorTxt30">
                                                    <div class="errorTxt30"></div>
                                                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="input-field col s12 m6 ">
                                                    <input type="text" class="" id="date_joined" readonly="" value="{{ $values->date_joined }}" name="date_joined">
                                                   
                                                    <label for="date_joined" class="force-active">Date Joined</label>
                                                    
                                                </div>
                                               
                                                <div class="input-field col s12 m6">
                                                    <label for="privilege_card_no" class="force-active">Privilege Card Number *</label>
                                                    <input id="privilege_card_no" name="privilege_card_no" readonly="" value="{{ $values->privilege_card_no }}" type="text" data-error=".errorTxt31">
                                                    <div class="errorTxt31"></div>
                                                       
                                                </div>
                                            </div>
                                             <div class="row">
                                                 <div class="input-field col s12 m6">
                                                    <label for="nric_ic" class="force-active">New IC Number*</label>
                                                    <input id="nric_ic" name="nric_ic" type="text" value="{{$values->nric_new}}" readonly="" data-error=".errorTxt13">
                                                    <div class="errorTxt13"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="nric_old" class="force-active">NRIC OLD*</label>
                                                    <input id="nric_old" name="nric_old" value="{{ $values->nric_old }}" readonly="" type="text" data-error=".errorTxt32">
                                                    <div class="errorTxt32"></div>
                                                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="input-field col s12 m12 ">
                                                    <input type="text" class="" id="address" readonly="" value="{{ $values->address }}" name="address">
                                                   
                                                    <label for="address" class="force-active">Address</label>
                                                    
                                                </div>
                                            </div>
                                             <div class="row">
                                                 <div class="input-field col s12 m6 ">
                                                    <input type="text" class="" id="status_name" readonly="" value="{{ $values->status_name }}" name="status_name">
                                                   
                                                    <label for="status_name" class="force-active">Member Status</label>
                                                    
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="ecopark_card_status" class="force-active">Eco Park Card Status</label>
                                                    <input id="ecopark_card_status" name="ecopark_card_status" value="{{ $values->card_status }}" readonly="" type="text" data-error=".errorTxt32">
                                                    <div class="errorTxt32"></div>
                                                       
                                                </div>
                                            </div>
                                             <h4 class="card-title">{{__('Attachments') }}</h4>
                                            
                                            <div class="col s12 m12">
                                                <table id="filetable">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="42%">File</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="attachmentarea">
                                                        @if(count($data['card_files'])>0)
                                                        @foreach( $data['card_files'] as $file)
                                                        <tr id="del_{{ $file->id }}">
                                                            
                                                            <td>{{$file->file_name}}  &nbsp;&nbsp; </td>
                                                            <td>
                                                                <a href="{{ asset('storage/app/privilege_card/'.$file->file_name) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a>
                                                                <!-- <a href="#" onclick="return DeleteImage('{{ $file->id }}')">
                                                                     Delete
                                                                </a> -->
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                    
                                                 </table>
                                                 <br>
                                            </div>
                                            <h4 class="card-title">{{__('Approval/Rejetion') }}</h4>
                                           
                                             <div class="col s12 m12 ">
                                                <div class="row">
                                                   
                                                    <div class="col s12 m3">
                                                        <label>Status*</label>
                                                         <select name="status" id="status" onclick="return EnableAdditionalFileds(this.value)" required="" class="error browser-default">
                                                            <option disabled="" >Select Status</option>
                                                            <option value="0" {{ $values->status == 0 ? 'selected' : '' }} > Pending</option>
                                                            <option value="1" {{ $values->status == 1 ? 'selected' : '' }}>Approved</option>
                                                            <option value="2" {{ $values->status == 2 ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                        <div class="input-field "> 
                                                        </div>
                                                       
                                                    </div>

                                                    <div id="reject_datesection" class="input-field col s12 m3  @if($values->status == 0) hide @endif">
                                                        <input type="text" value="{{ date('d/m/Y') }}" style="margin-top: 5px;" class="datepicker" id="approval_reject_date" placeholder="" name="approval_reject_date">
                                                        <label for="approval_reject_date" class="">Date</label>
                                                        <div class=""></div>
                                                    </div>

                                                    <div id="reject_reasonsection" class="input-field  col s12 m6 @if($values->status != 2) hide @endif">
                                                        <div class="">
                                                            <input name="approval_reject_reason" style="margin-top: 5px;" placeholder="" id="approval_reject_reason" type="text" value="{{ $values->approval_reject_reason }}" width="1200px;" class="validate" style="">
                                                        </div>
                                                        <label for="approval_reject_reason" class="">Remarks</label>
                                                        <input type="text" value="{{ Auth::user()->id }}" class="hide" id="approval_reject_by" name="approval_reject_by">
                                                    </div>
                                                    <div id="npc_status_section" class="col s12 m5 @if($values->status != 1) hide @endif">
                                                        <label for="pc_status_id" class="">NPC Status</label>
                                                         <select name="pc_status_id" id="pc_status_id" class="error browser-default">
                                                            <option value="" >Select Status</option>
                                                            @foreach($data['card_status_list'] as $cstatus)
                                                                <option {{ $cstatus->id == $values->pc_status_id ? 'selected' : '' }} value="{{ $cstatus->id }}" >{{ $cstatus->status_name }}</option>
                                                            @endforeach
                                                        </select>
                                                      
                                                       
                                                    </div>
                                                   
                                                    
                                                </div>
                                            </div>
                                          
                                            <div class="row">
                                              
												<div class="clearfix" style="clear:both"></div>
												<div class="input-field col s12">
													<button id="form-save-btn" class="btn waves-effect waves-light right submit"
														type="submit" name="action">
														@if(isset($values))
														{{__('Update') }}
														@else{{__('Save') }}
														@endif
													</button>
												</div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Page Main-->
                    @include('layouts.right-sidebar')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerSection')

<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>

$("#ecopark_sidebars_id").addClass('active');
$("#privilegecard_sidebar_li_id").addClass('active');
$("#privilegecard_sidebar_a_id").addClass('active');

 $('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoHide: true,
});

function EnableAdditionalFileds(type) {
    if(type==1){
        $("#reject_reasonsection").addClass('hide');
        $("#reject_datesection").removeClass('hide');
        $("#npc_status_section").removeClass('hide');
    }else if(type==2){
        $("#reject_reasonsection").removeClass('hide');
        $("#reject_datesection").removeClass('hide');
        $("#npc_status_section").addClass('hide');
    }else{
        $("#reject_reasonsection").addClass('hide');
        $("#reject_datesection").addClass('hide');
        $("#npc_status_section").addClass('hide');

    }
}

</script>
@endsection