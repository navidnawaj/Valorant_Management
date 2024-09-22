<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function player(){
        return $this->belongsTo(User::class, 'player_id');
    }

    public function team(){
        return $this->belongsTo(User::class, 'team_id');
    }
}
