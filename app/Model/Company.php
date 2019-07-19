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

    public function saveCompanydata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Company::find($data['id'])->update($data);
        } else {
            $savedata = Company::create($data);
        }
        return $savedata;
    }
}
