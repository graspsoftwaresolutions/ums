<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Race extends Model
{
    protected $table = 'race';
    protected $fillable = ['id','race_name','status'];
    public $timestamps = true;

    public function StoreRace($race)
    {
        $id = DB::table('race')->insertGetId($race);
        return $id;
    }
}
