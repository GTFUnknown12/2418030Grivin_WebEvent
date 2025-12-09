<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function index()
    {
        if (Auth::guard('pembeli')->check()) {
            $pembeli = Auth::guard('pembeli')->user();
            $tickets = Ticket::where('pembeli_id', $pembeli->id_pembeli)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('index-user', compact('tickets'));
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

        return redirect()->route('index.user')->with('success', 'Tiket berhasil dipesan!');
    }

    // ✅ METHOD EXPORT PDF YANG BERFUNGSI
    public function exportPDF()
    {
        // Cek apakah user sudah login
        if (!Auth::guard('pembeli')->check()) {
            return redirect()->route('login');
        }

        $pembeli = Auth::guard('pembeli')->user();
        
        // Ambil semua tiket user
        $tickets = Ticket::where('pembeli_id', $pembeli->id_pembeli)
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika tidak ada tiket
        if ($tickets->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tiket untuk diexport.');
        }

        // Data untuk PDF
        $data = [
            'title' => 'Laporan Tiket - ' . $pembeli->nama_pembeli,
            'date' => date('d/m/Y H:i:s'),
            'pembeli' => $pembeli,
            'tickets' => $tickets,
            'totalTickets' => $tickets->count(),
            'totalAmount' => $tickets->sum('total_harga'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('tickets.export-pdf', $data);
        
        // Set nama file
        $filename = 'tiket-' . str_replace(' ', '-', strtolower($pembeli->nama_pembeli)) . '-' . date('Y-m-d') . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }

    public function show($id)
    {
        $ticket = Ticket::where('id_tiket', $id)
            ->where('pembeli_id', Auth::guard('pembeli')->user()->id_pembeli)
            ->firstOrFail();
            
        return view('tickets.show', compact('ticket'));
    }
}