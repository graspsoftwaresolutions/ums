<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Persontitle extends Model
{
    protected $table = 'persontitle';
    protected $fillable = ['id','person_title','status'];
    public $timestamps = true;

    public function StorePersontitle($person)
    {
        $id = DB::table('persontitle')->insertGetId($person);
        return $id;
    }
}
