<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pembelis';
    protected $primaryKey = 'id_pembeli';
    
    protected $fillable = [
        'nama_pembeli',
        'username',
        'password',
        'alamat',
        'email',
        'jenis_kelamin',
        'tanggal_lahir',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_admin' => 'boolean',
        'create_at' => 'datetime',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'pembeli_id', 'id_pembeli');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'pembeli_id', 'id_pembeli');
    }

    // ========== ADMIN METHODS ==========
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->is_admin === true || $this->is_admin == 1;
    }

    /**
     * Get admin status as string
     */
    public function getAdminStatusAttribute()
    {
        return $this->is_admin ? 'Admin' : 'User';
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->nama_pembeli;
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope for regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('is_admin', false);
    }
}