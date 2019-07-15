  <!-- card stats start -->
<div id="card-stats">
   <div class="row">
      <div class="col s12 m6 l3">
         <div class="card animate fadeLeft">
            <div class="card-content red accent-2 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>No of Members</p>
               <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
              
            </div>
            <div class="card-action red">
               <div id="sales-compositebar" class="center-align"><a style="color:white" href="{{url('membership')}}">Members List</a></div>
            </div>
         </div>
      </div>
      
   </div>
</div>