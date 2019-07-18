<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $table="form_type";
    protected $fillable = ['id','formname','orderno','status'];
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
}
