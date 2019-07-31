<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubscription extends Model
{
    protected $table = "mon_sub";
    protected $fillable = ['id','Date','IsMonthEndclosed'];
    public $timestamps = false;
    //save 
    public function save_monthly_subscription($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = MonthlySubscription::find($data['id'])->update($data);
        } else {
            $savedata = MonthlySubscription::create($data);
        }
        return $savedata;
    }
}
