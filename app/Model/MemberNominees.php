<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberNominees extends Model
{
    protected $table = 'member_nominees';
	
	public function members(){
	    return $this->belongsTo(Membership::class, 'member_id');
	}
}
