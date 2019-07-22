<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Role;
class AppForm extends Model
{
    protected $table = "app_form";
    protected $fillable = ['id','formname','formtype_id','orderno',
                        'route','isactive','isinsert','isupdate','isdelete','ismenu','description','status'];
 
    public $timestamps = true;
	
	public function form()
    {
        return $this->belongsTo('App\Model\FormType','formtype_id')->orderBy('orderno', 'ASC');
    }
	
	public function saveAppFormdata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = AppForm::find($data['id'])->update($data);
        } else {
            $savedata = AppForm::create($data);
        }
        return $savedata;
    }
   
}
