<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AppForm extends Model
{
    protected $table = "app_form";
    protected $fillable = ['id','formname','formtype','orderno',
                        'route','isactive','isinsert','isupdate','isdelete','ismenu','description','status'];
 
    public $timestamps = true;
}
