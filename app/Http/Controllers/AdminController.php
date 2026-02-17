<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DeliveryChallan;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        // ── Clients ──────────────────────────────────────────────
        $totalClients = Client::count();

        // ── Invoices ─────────────────────────────────────────────
        $totalInvoices    = Invoice::count();
        $paidInvoices     = Invoice::where('status', 'paid')->count();
        $pendingInvoices  = Invoice::where('status', 'pending')->count();
        $overdueInvoices  = Invoice::where('status', 'overdue')->count();
        $partialInvoices  = Invoice::where('status', 'partial_paid')->count();

        $totalInvoiceAmount     = Invoice::sum('grand_total');
        $totalCollected         = Invoice::sum('paid_amount');
        $totalOutstanding       = Invoice::whereIn('status', ['pending', 'partial_paid', 'overdue'])
                                        ->selectRaw('SUM(grand_total - paid_amount) as outstanding')
                                        ->value('outstanding') ?? 0;
        $totalOverdueAmount     = Invoice::where('status', 'overdue')
                                        ->selectRaw('SUM(grand_total - paid_amount) as overdue_amt')
                                        ->value('overdue_amt') ?? 0;

        // ── Delivery Challans ─────────────────────────────────────
        $totalChallans     = DeliveryChallan::count();
        $totalChallanValue = DeliveryChallan::sum('total_amount');

        // Challans this month
        $challansThisMonth = DeliveryChallan::whereMonth('challan_date', now()->month)
                                            ->whereYear('challan_date', now()->year)
                                            ->count();

        // ── Quotations ────────────────────────────────────────────
        $totalQuotations     = Quotation::count();

        // Quotations this month
        $quotationsThisMonth = Quotation::whereMonth('date', now()->month)
                                        ->whereYear('date', now()->year)
                                        ->count();

        // ── Recent Activity ───────────────────────────────────────
        $recentInvoices  = Invoice::with('getClient')
                                ->latest()
                                ->take(5)
                                ->get();

        $recentChallans  = DeliveryChallan::with('client')
                                        ->latest()
                                        ->take(5)
                                        ->get();

        $recentQuotations = Quotation::with('client')
                                    ->latest()
                                    ->take(5)
                                    ->get();

        $data = [
            'pageTitle' => 'Dashboard',
            'user'      => User::findOrFail(auth()->id()),

            // Clients
            'totalClients'        => $totalClients,

            // Invoice counts
            'totalInvoices'       => $totalInvoices,
            'paidInvoices'        => $paidInvoices,
            'pendingInvoices'     => $pendingInvoices,
            'overdueInvoices'     => $overdueInvoices,
            'partialInvoices'     => $partialInvoices,

            // Invoice amounts
            'totalInvoiceAmount'  => $totalInvoiceAmount,
            'totalCollected'      => $totalCollected,
            'totalOutstanding'    => $totalOutstanding,
            'totalOverdueAmount'  => $totalOverdueAmount,

            // Delivery challans
            'totalChallans'       => $totalChallans,
            'totalChallanValue'   => $totalChallanValue,
            'challansThisMonth'   => $challansThisMonth,

            // Quotations
            'totalQuotations'     => $totalQuotations,
            'quotationsThisMonth' => $quotationsThisMonth,

            // Recent activity
            'recentInvoices'      => $recentInvoices,
            'recentChallans'      => $recentChallans,
            'recentQuotations'    => $recentQuotations,
        ];

        return view('back.pages.dashboard', $data);
    }

    public function logoutHandler(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You are now logged out');
    }
}
