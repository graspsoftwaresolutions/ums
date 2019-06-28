<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reason';
    protected $fillable = ['id','reason_name','status'];
    public $timestamps = true;

    public function StoreReason($reason)
    {
        $id = DB::table('reason')->insertGetId($reason);
        return $id;
    }
}
