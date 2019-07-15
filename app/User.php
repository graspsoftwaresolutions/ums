<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Permissions\HasPermissionsTrait;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users";
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function sendPasswordResetNotification($token)
	{
	   $this->notify( new ResetPassword($token));
    }

    public function unionbranch()
    {
        return $this->hasOne('App\Model\UnionBranch');
    }

    public function saveUserdata($data=array())
    {
       
        if (!empty($data['id'])) {
            $savedata = User::find($data['id'])->update($data);
        } else {
            $savedata = User::create($data);
        }
        return $savedata;
    }
}
