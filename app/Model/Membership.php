<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Membership extends Model
{
    protected $table = "membership";
    protected $fillable = ['id','member_title_id','member_number','name','gender','designation_id','email','phone','country_id','state_id','city','address_one',
                                'address_two','address_three','race_id','old_ic','new_ic','dob','doj','branch_id','levy','levy_amount','tdf','tdf_amount','email_verified_at','password','user_type','is_request_approved','status'];
    public $timestamps = true;

    public function StoreMembership($member){
        $id = DB::table('membership')->insertGetId($member);
        return $id;
    }
    
    /* one to many */
	public function nominees(){
		return $this->hasMany(MemberNominees::class, 'member_id');
    }
    public function Status()
    {
        return $this->belongsTo('App\Model\Status');
    }
}
