<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrim extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teamFrom()
    {
        return $this->belongsTo(User::class, 'team_from_id');
    }

    public function teamTo()
    {
        return $this->belongsTo(User::class, 'team_to_id');
    }
}
