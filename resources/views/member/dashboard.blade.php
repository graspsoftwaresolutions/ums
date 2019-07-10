<div class="row">
   
   <div class="col s12 m6 l8">
      <div class="card subscriber-list-card animate fadeRight">
         <div class="card-content">
            <h4 class="card-title mb-0">Member Dashboard <i class="material-icons float-right">more_vert</i></h4>
			</br>
			@php
			 $user_id = Auth::user()->id;
			 $accountstatus = CommonHelper::getaccountStatus($user_id);
			@endphp
			@if($accountstatus==1)
			<span class="badge new badge pill pink">Your account is not verified yet</span>
			@endif
			@if($accountstatus==2)
			<span class="badge new badge pill green">Your account is Verified</span>
			@endif
         </div>
      </div>
   </div>
</div>

