<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reason';
    protected $fillable = ['id','reason_name','status'];
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
