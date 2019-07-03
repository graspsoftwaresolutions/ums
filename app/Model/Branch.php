<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'id','company_id','union_branch_id','branch_name','country_id','state_id','city_id','postal_code','address_one','address_two','address_three','phone','mobile','email','status',
    ];
    public function StoreBranch($branch)
    {
        $id = DB::table('branch')->insertGetId($branch);
        return $id;
    }
}
