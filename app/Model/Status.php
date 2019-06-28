<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Status extends Model
{
    protected $fillable = [
        'id','country_id','status_name','status',
    ];
    public $timestamps = true;

    public function StoreStatus($status)
    {
        $id = DB::table('status')->insertGetId($status);
        return $id;
    }
}
