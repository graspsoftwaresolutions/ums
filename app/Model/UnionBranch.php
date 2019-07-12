<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class UnionBranch extends Model
{
    protected $table = 'union_branch';
    protected $fillable = ['id','union_branch','is_head','country_id','state_id','city_id','postal_code','address_one','address_two','phone','email','status'];
    public $timestamps = true;

    public function StoreUnionBranch($union)
    {
        $id = DB::table('union_branch')->insertGetId($union);
        return $id;
    }
    public function User()
    {
        return $this->hasmany('App\User');
    }
}
