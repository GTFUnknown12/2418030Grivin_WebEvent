<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'pembeli_id',
        'ticket_id',
        'amount',
        'type',
        'status',
        'description',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id', 'id_pembeli');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id_tiket');
    }
}