<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Users extends Model
{
    protected $table = 'users';
    // public $incrementing = 'false';
    public $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = ['username', 'password', 'email', 'image', 'remember_token', 'oauth'];
    protected $hidden = ['password', 'remember_token',];
    protected $casts = ['oauth' => 'boolean',];

    public function ShowCards()
    {
        return $this->belongsToMany('App\Card', 'groups', 'users_id', 'card_id');
    }
    public function ShowGroups()
    {
        return $this->hasMany('App\Groups');
    }
    public function GetToken()
    {
        do {
            $token = Str::random(15);
            $tokenCheck = Users::where('remember_token', $token)->first();
            if (isset($tokenCheck)) {
                $sameToken = true;
            } else {
                $sameToken = false;
            }
        } while ($sameToken);
        return $token;
    }
}
