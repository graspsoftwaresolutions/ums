<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class state extends Model
{
    protected $fillable = [
        'id','country_id','state_name','status',
    ];
    public function StoreState($state)
    {
        $id = DB::table('state')->insertGetId($state);
        return $id;
    }
}
