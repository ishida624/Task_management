<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'user';
    // public $incrementing = 'false';
    public $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = ['username', 'password', 'email', 'image', 'remember_token'];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
