<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class UnionBranch extends Model
{
    protected $table = 'union_branch';
    protected $fillable = ['id','union_branch','is_head','status'];
    public $timestamps = true;

    public function StoreUnionBranch($union)
    {
        $id = DB::table('union_branch')->insertGetId($union);
        return $id;
    }
}
