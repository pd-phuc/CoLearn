<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with('user', 'order');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        $totalIn = Transaction::where('type', 'in')->sum('amount');
        $totalOut = Transaction::where('type', 'out')->sum('amount');
        $totalCount = Transaction::count();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'totalCount',
        ));
    }
}
