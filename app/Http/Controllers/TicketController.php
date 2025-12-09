<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        if (Auth::guard('pembeli')->check()) {
            $pembeli = Auth::guard('pembeli')->user();
            $tickets = Ticket::where('pembeli_id', $pembeli->id_pembeli)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('tickets.index', compact('tickets'));
        }

        return redirect()->route('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tiket' => 'required|string|max:255',
            'jumlah_tiket' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:Transfer Bank,E-Wallet,COD',
        ]);

        $total_harga = $request->jumlah_tiket * $request->harga_satuan;

        $ticket = Ticket::create([
            'pembeli_id' => Auth::guard('pembeli')->id(),
            'judul_tiket' => $request->judul_tiket,
            'jumlah_tiket' => $request->jumlah_tiket,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $total_harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'pending',
        ]);

        // Create transaction record
        Transaction::create([
            'transaction_id' => 'TRX' . time() . rand(1000, 9999),
            'pembeli_id' => Auth::guard('pembeli')->id(),
            'ticket_id' => $ticket->id_tiket,
            'amount' => $total_harga,
            'type' => 'payment',
            'status' => 'pending',
            'payment_method' => $request->metode_pembayaran,
            'description' => "Pembelian tiket: {$request->judul_tiket}",
        ]);

        // PERBAIKAN: Gunakan route 'index.user' bukan 'index-user'
        return redirect()->route('index.user')->with('success', 'Tiket berhasil dipesan!');
    }

    // ... methods lainnya tetap sama
}