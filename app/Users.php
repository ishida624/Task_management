<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    // public $incrementing = 'false';
    public $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = ['username', 'password', 'email', 'image', 'remember_token'];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function ShowCards()
    {
        return $this->belongsToMany('App\Card', 'groups', 'users_id', 'card_id');
    }
}
