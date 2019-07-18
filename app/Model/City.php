<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class City extends Model
{
    protected $table = 'city';
    protected $fillable = ['id','country_id','state_id','country_name','city_name','status'];
    public $timestamps = true;

    public function saveCitydata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = City::find($data['id'])->update($data);
        } else {
            $savedata = City::create($data);
        }
        return $savedata;
    }
    public function state()
    {
        return $this->belongsTo('App\Model\State');
    }
}
