<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubscriptionMember extends Model
{
    protected $table = "monthlysubscriptionmember";
    protected $fillable = ['id','MonthlySubscriptionCompanyId','MemberCode','StatusId','NRIC'];
    public $timestamps = true;
    //save 
    public function save_monthly_subMember($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = MonthlySubscriptionMember::find($data['id'])->update($data);
        } else {
            $savedata = MonthlySubscriptionMember::create($data);
        }
        return $savedata;
    }
    public function status()
    {
        return $this->belongsTo('App\Model\Status');
    }
}
