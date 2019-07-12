<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $table="form_type";
    protected $fillable = ['id','formname','status'];
    public $timestamps = true;
}
