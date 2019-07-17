<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Persontitle extends Model
{
    protected $table = 'persontitle';
    protected $fillable = ['id','person_title','status'];
    public $timestamps = true; 

    public function savePersonTitledata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Persontitle::find($data['id'])->update($data);
        } else {
            $savedata = Persontitle::create($data);
        }
        return $savedata;
    }
    
}
