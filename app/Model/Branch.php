<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'id','company_id','branch_name','address_one','address_two','country_id','state_id','city_id','postal_code','email','phone','status',
    ];
    public function StoreBranch($branch)
    {
        $id = DB::table('branch')->insertGetId($branch);
        return $id;
    }
}
