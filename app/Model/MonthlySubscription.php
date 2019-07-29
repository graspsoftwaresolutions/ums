<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubscription extends Model
{
    protected $table = "monthlysubscription";
    protected $fillable = ['id','Date','IsMonthEndclosed'];
    public $timestamps = true;
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
