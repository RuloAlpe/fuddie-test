<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function usuarioValido($api_token){
        $user = DB::table('users')->where('api_token', $api_token)->first();
        if($user)
            return $user;

        return false;
    }

    // static function showUser($api_token){
    //     $user = DB::table('users')->where('api_token', $api_token)->first();
    //     if($user)
    //         return $user;

    //     return false;
    // }

    static function hasAccount($id_user){
        $account = DB::table('accounts')->where('id_user', $id_user)->first();
        if($account){
            return true;
        }
        return false;
    }

    static function deleteUser($id){
        if(DB::table('users')->where('id', $id)->delete())
            return true;

        return false;
    }
}
