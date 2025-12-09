<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        return view('home', compact('events'));
    }

    public function userDashboard()
    {
        if (!Auth::guard('pembeli')->check()) {
            return redirect()->route('login');
        }

        $events = Event::where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        $pembeli = Auth::guard('pembeli')->user();
        $tickets = \App\Models\Ticket::where('pembeli_id', $pembeli->id_pembeli)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('index-user', compact('events', 'pembeli', 'tickets'));
    }
}