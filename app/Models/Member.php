<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Member extends User
{
    //
    protected $fillable=[
        'username','password','tel',
        'rememberToken','status'
    ];
}
