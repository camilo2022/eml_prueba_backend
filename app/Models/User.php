<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'names',
        'last_names',
        'number_phone',
        'email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    public function scopeSearch($query, $search)
    {
        // Aplica un filtro de búsqueda sobre el query, buscando coincidencias parciales en varias columnas
        return $query->where('id', 'LIKE', '%' . $search . '%') // Busca en la columna 'id'
        ->orWhere('names', 'LIKE', '%' . $search . '%') // Busca en la columna 'names'
        ->orWhere('last_names', 'LIKE', '%' . $search . '%') // Busca en la columna 'last_names'
        ->orWhere('number_phone', 'LIKE', '%' . $search . '%') // Busca en la columna 'number_phone'
        ->orWhere('email', 'LIKE', '%' . $search . '%'); // Busca en la columna 'email'
    }
}
