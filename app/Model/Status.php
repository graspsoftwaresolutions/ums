<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Status extends Model
{
    protected $table = 'status';
    protected $fillable = [
        'id','status_name','status',
    ];
    public $timestamps = true;

    public function saveStatusdata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Status::find($data['id'])->update($data);
        } else {
            $savedata = Status::create($data);
        }
        return $savedata;
    }

    public function Members()
    {
        return $this->hasMany('App\Modal\Membership','status_id');
    }
    public function Subscription_members()
    {
        return $this->hasMany('App\Model\MonthlySubscriptionMember','StatusId');
    }
}
