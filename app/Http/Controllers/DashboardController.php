<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pembeli' => Pembeli::count(),
            'total_tickets' => Ticket::count(),
            'pending_tickets' => Ticket::where('status_pembayaran', 'pending')->count(),
            'completed_tickets' => Ticket::where('status_pembayaran', 'completed')->count(),
            'failed_tickets' => Ticket::where('status_pembayaran', 'failed')->count(),
            'revenue' => Ticket::where('status_pembayaran', 'completed')->sum('total_harga'),
            'total_events' => Event::where('is_active', true)->count(),
        ];

        $recent_tickets = Ticket::with('pembeli')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_pembelis = Pembeli::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tickets', 'recent_pembelis'));
    }

    public function admin()
    {
        $pembelis = Pembeli::orderBy('id_pembeli', 'desc')->paginate(10);
        $tickets = Ticket::with('pembeli')
            ->orderBy('id_tiket', 'desc')
            ->paginate(10);

        $total_pembeli = Pembeli::count();
        $approved_count = Ticket::where('status_pembayaran', 'completed')->count();
        $pending_count = Ticket::where('status_pembayaran', 'pending')->count();

        return view('admin.admin', compact('pembelis', 'tickets', 'total_pembeli', 'approved_count', 'pending_count'));
    }

    public function transactions()
    {
        $transactions = Transaction::with(['pembeli', 'ticket'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_income' => Transaction::where('type', 'payment')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_expenses' => Transaction::whereIn('type', ['refund', 'withdrawal'])
                ->where('status', 'completed')
                ->sum('amount'),
            'total_transactions' => Transaction::count(),
            'success_rate' => Transaction::where('status', 'completed')->count() / max(Transaction::count(), 1) * 100,
        ];

        return view('admin.transactions', compact('transactions', 'stats'));
    }
}