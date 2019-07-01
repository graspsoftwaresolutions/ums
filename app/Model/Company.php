<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{
    protected $table = "company";
    protected $fillable = [
        'id','company_name','owner_name','phone','email','address_one','address_two','status'
    ];
    public $timestamps = true;
     
    public function StoreCompany($company)
    {
        $id = DB::table($this->table)->InsertGetId($company);
        return $id;
    }
}
