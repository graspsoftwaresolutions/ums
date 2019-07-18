<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class state extends Model
{
    protected $table = 'state';
    protected $fillable = [
        'id','country_id','state_name','status',
    ];
    public function saveStatedata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = state::find($data['id'])->update($data);
        } else {
            $savedata = state::create($data);
        }
        return $savedata;
    }
    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }
    public function cities(){
        return $this->hasMany('App\Model\City');
    }
    public $timestamps = true;
}
