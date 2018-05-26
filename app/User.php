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


    /**
     * Checks if user has admin permissions
     *
     * @return boolean
     */
    public function isAdmin(){

        if($this->is_admin === 1){
            return true;
        } else {
            return false;
        }

    }

    /**
     * Checks if user has an image
     *
     * @return string
     */
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
