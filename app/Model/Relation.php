<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relation';
    protected $fillable = ['id','relation_name','status'];
    public $timestamps = true;

    public function StoreRelation($relation)
    {
        $id = DB::table('relation')->insertGetId($relation);
        return $id;
    }
}
