<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'id','company_id','branch_name','status',
    ];
    public function StoreBranch($branch)
    {
        $id = DB::table('branch')->insertGetId($branch);
        return $id;
    }
}
