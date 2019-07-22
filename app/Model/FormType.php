<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Role;

class FormType extends Model
{
    protected $table="form_type";
    protected $fillable = ['id','formname','orderno','module','status'];
    public $timestamps = true;
    
   public function saveFormTypedata($data=array())
    {
        //dd($data); 
        if (!empty($data['id'])) {
            $savedata = FormType::find($data['id'])->update($data);
        } else {
            $savedata = FormType::create($data);
        }
        return $savedata;
    }

	public function appforms()
    {
        return $this->hasmany('App\Model\AppForm','formtype_id')->orderBy('orderno', 'ASC');
    }

    public function roles() {
        return $this->belongsToMany('App\Role','roles_modules','module_id','role_id');
    }
}
