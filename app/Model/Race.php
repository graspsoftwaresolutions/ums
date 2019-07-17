<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Race extends Model
{
    protected $table = 'race';
    protected $fillable = ['id','race_name','status'];
    public $timestamps = true;

    public function saveRacedata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Race::find($data['id'])->update($data);
        } else {
            $savedata = Race::create($data);
        }
        return $savedata;
    }
}
