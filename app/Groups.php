<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'groups';
    public $primaryKey = 'id';
    protected $fillable = ['card_id', 'users_id',];
    public function ShowCards()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }
    public function ShowUsers()
    {
        return $this->belongsTo('App\Users', 'users_id');
    }
}
