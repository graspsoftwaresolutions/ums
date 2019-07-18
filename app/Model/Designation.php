<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designation';
    protected $fillable = ['id','designation_name','status'];
    public $timestamps = true;

    public function saveDesignationdata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Designation::find($data['id'])->update($data);
        } else {
            $savedata = Designation::create($data);
        }
        return $savedata;
    }
}
