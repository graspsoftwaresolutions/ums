<div class="row">

    <div class="col s12 m6 l8">
        @php
        $user_id = Auth::user()->id;
        $accountstatus = CommonHelper::getapprovedStatus($user_id);
        @endphp
        @if($accountstatus==0)
        <div class="card-alert-nonclose card red">
            <div class="card-content white-text">
                <p>SUCCESS : Your account is waiting for approval.</p>
            </div>
            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        @if($accountstatus==1)
        <div class="card-alert-nonclose card green">
            <div class="card-content white-text">
                <p>SUCCESS : Your account is Verified.</p>
            </div>
            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif

    </div>
</div>