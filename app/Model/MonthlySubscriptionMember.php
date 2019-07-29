<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubscriptionMember extends Model
{
    protected $table = "mon_sub_member";
    protected $fillable = ['id','MonthlySubscriptionCompanyId','MemberCode','StatusId','NRIC'];
    public $timestamps = false;
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
    public function CompanyId()
    {
        return $this->belongsTo('App\Model\Status');
    }
}
