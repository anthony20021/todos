<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listes extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'owner',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('owner', $userId);
    }
}