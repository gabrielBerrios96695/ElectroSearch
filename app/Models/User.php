<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'second_last_name',
        'email',
        'password',
        'role',
        'store_id',
        'userid',
        'status',
        'passwordUpdate',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }

    public function isVendedor()
    {
        return $this->role === 2;
    }

    public function isCliente()
    {
        return $this->role === 3;
    }

    // Relación con el modelo Sale
    public function sales()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    // Relación con el modelo Sale (como vendedor)
    public function salesAsSeller()
    {
        return $this->hasMany(Sale::class, 'seller_id');
    }
}
