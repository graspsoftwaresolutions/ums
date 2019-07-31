<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubMatchType extends Model
{
    protected $table = "mon_sub_match_table";
    protected $fillable = ['id','match_name','created_by','updated_by','updated_at','created_at','status'];
}
