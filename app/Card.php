<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'card';
    public $primaryKey = 'id';
    protected $fillable = ['card_name', 'create_user',];
    public function ShowGroups()
    {
        return $this->hasMany('App\Groups');
    }
    public function ShowTasks()
    {
        return $this->hasMany('App\Task');
    }
    public function ShowUsers()
    {
        return $this->belongsToMany('App\Users', 'groups');
    }
}
