<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{
    protected $table = "company";
    protected $fillable = [
        'id','company_name','short_code','head_of_company','status'
    ];
    public $timestamps = true;
     
    public function StoreCompany($company)
    {
        $id = DB::table($this->table)->InsertGetId($company);
        return $id;
    }
}
