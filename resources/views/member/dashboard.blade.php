<div class="row">
   
   <div class="col s12 m6 l8">
		@php
		 $user_id = Auth::user()->id;
		 $accountstatus = CommonHelper::getaccountStatus($user_id);
		@endphp
		@if($accountstatus==1)
			<div class="card-alert-nonclose card red">
				<div class="card-content white-text">
				  <p>SUCCESS : Your account is waiting for approval.</p>
				</div>
				<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
			</div>
		@endif
		@if($accountstatus==2)
			<div class="card-alert-nonclose card green">
				<div class="card-content white-text">
				  <p>SUCCESS : Your account is Verified.</p>
				</div>
				<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
			</div>
		@endif
      <div class="card subscriber-list-card animate fadeRight">
         <div class="card-content">
            <h4 class="card-title mb-0">Member Dashboard <i class="material-icons float-right">more_vert</i></h4>
			</br>
			
         </div>
      </div>
   </div>
</div>

