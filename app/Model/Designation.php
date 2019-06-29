<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designation';
    protected $fillable = ['id','designation_name','status'];
    public $timestamps = true;

    public function StoreDesignation($designation)
    {
        $id = DB::table('designation')->insertGetId($designation);
        return $id;
    }
}
