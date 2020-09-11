<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    // public $incrementing = 'false';
    public $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = ['title', 'status', 'create_user', 'description', 'update_user', 'tag', 'image', 'card_id'];
    // protected $hidden = ['image'];
    protected $casts = [
        'status' => 'boolean',
    ];
}
