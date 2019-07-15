<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
{
    protected $table = 'country';
    protected $fillable = ['id','country_name','status'];
    public $timestamps = true;

    public function saveCountrydata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Country::find($data['id'])->update($data);
        } else {
            $savedata = Country::create($data);
        }
        return $savedata;
    }
    
}
