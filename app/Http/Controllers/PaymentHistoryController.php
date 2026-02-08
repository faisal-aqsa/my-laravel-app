<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentHistory;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'pageTitle' => 'Payment History',
            'payments' => PaymentHistory::with('invoice')
                ->latest()
                ->get()
        ];

        return view('back.pages.payment-history', $data);
    }
}
