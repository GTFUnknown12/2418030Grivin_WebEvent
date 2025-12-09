<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $primaryKey = 'id_tiket';
    
    protected $fillable = [
        'pembeli_id',
        'judul_tiket',
        'jumlah_tiket',
        'harga_satuan',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'jumlah_tiket' => 'integer',
    ];

    // Relationship
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id', 'id_pembeli');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'ticket_id', 'id_tiket');
    }
}