<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class City extends Model
{
    protected $table = 'city';
    protected $fillable = ['id','country_id','state_id','country_name','status'];
    public $timestamps = true;

    public function StoreCity($city)
    {
        $id = DB::table('city')->insertGetId($city);
        return $id;
    }
}
