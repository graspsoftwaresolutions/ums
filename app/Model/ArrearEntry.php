<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArrearEntry extends Model
{
    protected $table= "arrear_entry";
    protected $fillable = ['id','membercode','company_id','branch_id','nric','arrear_date','arrear_amount','no_of_months','created_by','updated_by','updated_at','created_at'];

    public function saveArreardata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = ArrearEntry::find($data['id'])->update($data);
        } else {
            $savedata = ArrearEntry::create($data);
        }
        return $savedata;
    }
}
