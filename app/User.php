<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

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

    public function isAdmin(){
        if($this->is_admin === 1){
            return true;
        } else {
            return false;
        }
    }

    public function getImage() {

        $avatar = 'default';
        if(!is_null($this->avatar)){
            $avatar = $this->avatar;
        }

        if(Storage::disk('local')->exists('public/avatars/'.$avatar)){
            return url(Storage::url('public/avatars/'.$this->avatar));
        } else {
            return url(Storage::url('public/avatars/profile-placeholder.png'));
        }
    }
}
