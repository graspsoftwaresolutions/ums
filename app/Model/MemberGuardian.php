<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class MemberGuardian extends Model
{
    protected $table = 'member_guardian';
    protected $fillable = ['member_id','guardian_name','relationship_id','country_id','state_id','postal_code','city_id','address_one','address_two','address_three','years','gender','nric_n','nric_o','mobile','phone','status'];
    public $timestamps = true;

    public function StoreMemberGaurdian($gaurdian)
    {
        $id = DB::table('member_guardian')->insertGetId($gaurdian);
        return $id;
    }
}
