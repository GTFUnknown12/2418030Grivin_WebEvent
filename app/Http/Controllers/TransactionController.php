<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
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

    public function store(Request $request)
    {
        $request->validate([
            'pembeli_id' => 'required|exists:pembelis,id_pembeli',
            'ticket_id' => 'required|exists:tickets,id_tiket',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:payment,refund,withdrawal',
            'status' => 'required|in:pending,completed,failed',
            'payment_method' => 'required|string',
        ]);

        $transaction = Transaction::create([
            'transaction_id' => 'TRX' . time() . rand(1000, 9999),
            'pembeli_id' => $request->pembeli_id,
            'ticket_id' => $request->ticket_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
        ]);

        return response()->json($transaction, 201);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:pending,completed,failed',
            'type' => 'sometimes|in:payment,refund,withdrawal',
        ]);

        $transaction->update($request->all());

        if ($request->has('status') && $transaction->ticket_id) {
            Ticket::where('id_tiket', $transaction->ticket_id)
                ->update(['status_pembayaran' => $request->status]);
        }

        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function viewTransaction($id)
    {
        $transaction = Transaction::with(['pembeli', 'ticket'])->findOrFail($id);
        return view('admin.transaction-details', compact('transaction'));
    }
}