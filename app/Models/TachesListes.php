<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TachesListes extends Model
{
    public $timestamps = false;

    // Définition des clés primaires composites
    protected $primaryKey = ['liste_id', 'tache_id'];

    protected $fillable = [
        'liste_id',
        'tache_id',
    ];

    // Indique à Eloquent de ne pas incrémenter les clés primaires
    public $incrementing = false;

    // Définit les types de données des clés primaires
    protected $keyType = 'int';

    // Relations avec les autres modèles
    public function liste()
    {
        return $this->belongsTo(Listes::class, 'liste_id');
    }

    public function tache()
    {
        return $this->belongsTo(Taches::class, 'tache_id');
    }

    // Scopes
    public function scopeOfListe($query, $listeId)
    {
        return $query->where('liste_id', $listeId);
    }

    public function scopeOfTache($query, $tacheId)
    {
        return $query->where('tache_id', $tacheId);
    }
}