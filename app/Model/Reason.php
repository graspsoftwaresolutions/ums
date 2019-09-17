<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reason';
    protected $fillable = ['id','reason_name','status','is_benefit_valid','minimum_year','minimum_refund','maximum_refund','five_year_amount','fiveplus_year_amount','one_year_amount'];
    public $timestamps = true;

    public function saveReasondata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Reason::find($data['id'])->update($data);
        } else {
            $savedata = Reason::create($data);
        }
        return $savedata;
    }
}
