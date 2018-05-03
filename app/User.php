<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*public function roles()
    {
        return $this->belongsToMany('App\Role');
    }*/

    /*public function is($roleName)
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->role == $roleName)
            {
                return true;
            }
        }

        return false;
    }*/

    public function getImage()
    {
        if(file_exists(public_path('/uploads/users/'.$this->id.'/').'profile.png')){
            return url('/images/uploads/users/'.$this->id.'/').'/profile.png';
        } else {
            return url('/images/uploads/users/').'/profile-placeholder.png';
        }
    }
}
