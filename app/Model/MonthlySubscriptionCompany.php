<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthlySubscriptionCompany extends Model
{
    protected $table = "mon_sub_company";
    protected $fillable = ['id','MonthlySubscriptionId','CompanyCode','created_by'];
	public $timestamps = false;
}
