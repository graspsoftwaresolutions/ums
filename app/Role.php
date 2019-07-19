<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions() {
    	return $this->belongsToMany(Permission::class,'roles_permissions');
    }
    protected $fillable = ['id','slug','name'];
    protected $table = 'roles';
    public $timestamps = true;
	
	public function saveRoledata($data=array())
    {
        if (!empty($data['id'])) {
            $savedata = Role::find($data['id'])->update($data);
        } else {
            $savedata = Role::create($data);
        }
            return $savedata;
    }
	
}
