<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersListes extends Model
{
    public $timestamps = false;

    // Indique à Eloquent de ne pas incrémenter les clés primaires
    public $incrementing = false;

    // Définit les types de données des clés primaires
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'liste_id',
    ];

    // Relations avec les autres modèles
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function liste()
    {
        return $this->belongsTo(Listes::class, 'liste_id');
    }

    // Scopes
    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfListe($query, $listeId)
    {
        return $query->where('liste_id', $listeId);
    }


    public function delete()
    {
        return static::where('user_id', $this->user_id)
            ->where('liste_id', $this->liste_id)
            ->delete();
    }
}