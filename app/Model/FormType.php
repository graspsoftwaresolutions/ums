<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $table="form_type";
    protected $fillable = ['id','formname','status'];
    public $timestamps = true;
	
	public function appforms()
    {
        return $this->hasmany('App\Model\AppForm','formtype_id');
    }
}
