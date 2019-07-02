<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = 'fee';
    protected $fillable = ['id','fee_name','fee_amount','status'];
    public $timestamps = true;

    public function StoreFee($fee)
    {
        $id = DB::table('fee')->insertGetId($fee);
        return $id;
    }
}
