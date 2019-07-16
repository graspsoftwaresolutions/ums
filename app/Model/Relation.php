<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relation';
    protected $fillable = ['id','relation_name','status'];
    public $timestamps = true;

    public function saveRelationdata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Relation::find($data['id'])->update($data);
        } else {
            $savedata = Relation::create($data);
        }
        return $savedata;
    }
}
