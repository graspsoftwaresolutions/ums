<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubMemberMatch extends Model
{
    protected $table = "mon_sub_member_match";
	protected $fillable = ['id','mon_sub_member_id','match_id','created_by'];
	public $timestamps = false;
}
