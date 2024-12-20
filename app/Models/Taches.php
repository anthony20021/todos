<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taches extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'user_id',
        'checked',
        'assi'
    ];

    protected $appends = ['modif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getModifAttribute()
    {
        return false;
    }
}
