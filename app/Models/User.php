<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'verif_code',
        'verfyed',
        'profil_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    // Relation avec les permissions à travers les rôles
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    // Vérifie si l'utilisateur a une permission spécifique
    public function hasPermission($permission)
    {
        return $this->permissions()->contains('permission', $permission);
    }

    // Vérifie si l'utilisateur a un rôle spécifique
    public function hasRole($role)
    {
        return $this->roles()->contains('name', $role);
    }

    public function photo()
    {
        return $this->belongsTo(Document::class, 'profil_img');
    }
}
