@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')

@push('custom-styles')
<style>
    /* ── Dashboard CSS Variables ────────────────────────── */
    :root {
        --dash-blue:    #2563eb;
        --dash-green:   #16a34a;
        --dash-red:     #dc2626;
        --dash-yellow:  #d97706;
        --dash-purple:  #7c3aed;
        --dash-teal:    #0891b2;
        --dash-pink:    #db2777;
        --dash-gray:    #6b7280;
        --card-radius:  12px;
        --card-shadow:  0 2px 12px rgba(0,0,0,.07);
    }

    /* ── Stat Cards ─────────────────────────────────────── */
    .dash-stat-card {
        border: none;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        transition: transform .18s ease, box-shadow .18s ease;
        overflow: hidden;
        position: relative;
    }
    .dash-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
    }
    .dash-stat-card .card-body {
        padding: 22px 24px;
    }
    .dash-stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .dash-stat-card .stat-value {
        font-size: 26px;
        font-weight: 700;
        line-height: 1.15;
        color: #1a1a2e;
        margin: 0;
    }
    .dash-stat-card .stat-label {
        font-size: 12.5px;
        font-weight: 500;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 3px 0 0 0;
    }
    .dash-stat-card .stat-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        margin-top: 10px;
        display: inline-block;
    }

    /* Color stripe on left border */
    .dash-stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }
    .dash-stat-card.border-blue::before   { background: var(--dash-blue); }
    .dash-stat-card.border-green::before  { background: var(--dash-green); }
    .dash-stat-card.border-red::before    { background: var(--dash-red); }
    .dash-stat-card.border-yellow::before { background: var(--dash-yellow); }
    .dash-stat-card.border-purple::before { background: var(--dash-purple); }
    .dash-stat-card.border-teal::before   { background: var(--dash-teal); }
    .dash-stat-card.border-pink::before   { background: var(--dash-pink); }
    .dash-stat-card.border-gray::before   { background: var(--dash-gray); }

    /* Icon bg helpers */
    .icon-blue   { background: #eff6ff; color: var(--dash-blue); }
    .icon-green  { background: #f0fdf4; color: var(--dash-green); }
    .icon-red    { background: #fef2f2; color: var(--dash-red); }
    .icon-yellow { background: #fffbeb; color: var(--dash-yellow); }
    .icon-purple { background: #f5f3ff; color: var(--dash-purple); }
    .icon-teal   { background: #ecfeff; color: var(--dash-teal); }
    .icon-pink   { background: #fdf2f8; color: var(--dash-pink); }
    .icon-gray   { background: #f9fafb; color: var(--dash-gray); }

    /* Badge helpers */
    .badge-blue   { background: #eff6ff; color: var(--dash-blue); }
    .badge-green  { background: #f0fdf4; color: var(--dash-green); }
    .badge-red    { background: #fef2f2; color: var(--dash-red); }
    .badge-yellow { background: #fffbeb; color: var(--dash-yellow); }
    .badge-purple { background: #f5f3ff; color: var(--dash-purple); }
    .badge-teal   { background: #ecfeff; color: var(--dash-teal); }

    /* ── Section Headers ─────────────────────────────────── */
    .dash-section-header {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #9ca3af;
        padding: 8px 0 12px 0;
        margin-bottom: 0;
        border-bottom: 2px solid #f3f4f6;
    }

    /* ── Summary Amount Cards ───────────────────────────── */
    .dash-amount-card {
        border: none;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    .dash-amount-card .amount-header {
        padding: 18px 22px 14px;
    }
    .dash-amount-card .amount-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .6px;
        opacity: .85;
        margin: 0 0 6px 0;
    }
    .dash-amount-card .amount-value {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
    }
    .dash-amount-card .amount-sub {
        font-size: 12px;
        opacity: .75;
        margin-top: 4px;
    }

    /* Colored amount cards */
    .amount-card-blue   { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; }
    .amount-card-green  { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; }
    .amount-card-red    { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
    .amount-card-yellow { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; }

    /* ── Recent Activity Tables ──────────────────────────── */
    .dash-activity-card {
        border: none;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
    }
    .dash-activity-card .card-header {
        background: #fff;
        border-bottom: 2px solid #f3f4f6;
        padding: 16px 20px;
        border-radius: var(--card-radius) var(--card-radius) 0 0;
    }
    .dash-activity-card .card-header h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dash-activity-card .card-header h6 i {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }
    .dash-activity-card .table {
        margin: 0;
        font-size: 13px;
    }
    .dash-activity-card .table thead th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #9ca3af;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 16px;
    }
    .dash-activity-card .table tbody td {
        padding: 11px 16px;
        vertical-align: middle;
        border-color: #f3f4f6;
        color: #374151;
    }
    .dash-activity-card .table tbody tr:hover td {
        background: #fafafa;
    }
    .dash-activity-card .table tbody tr:last-child td {
        border-bottom: none;
    }
    .dash-activity-card .view-all-link {
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        color: var(--dash-blue);
        padding: 14px 20px;
        display: block;
        text-align: center;
        border-top: 1px solid #f3f4f6;
        transition: background .15s;
    }
    .dash-activity-card .view-all-link:hover {
        background: #f9fafb;
    }

    /* ── Status Badges ───────────────────────────────────── */
    .status-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .status-paid        { background: #f0fdf4; color: #16a34a; }
    .status-pending     { background: #f9fafb; color: #6b7280; }
    .status-overdue     { background: #fef2f2; color: #dc2626; }
    .status-partial     { background: #fffbeb; color: #d97706; }

    /* ── Invoice breakdown mini-list ────────────────────── */
    .invoice-breakdown {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .invoice-breakdown li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13.5px;
    }
    .invoice-breakdown li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .invoice-breakdown .ib-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4b5563;
        font-weight: 500;
    }
    .invoice-breakdown .ib-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .invoice-breakdown .ib-count {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 14px;
    }

    /* ── Welcome banner ─────────────────────────────────── */
    .dash-welcome {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: #fff;
        border-radius: var(--card-radius);
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .dash-welcome h4 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
    }
    .dash-welcome p {
        margin: 0;
        font-size: 13.5px;
        opacity: .75;
    }
    .dash-welcome .date-chip {
        background: rgba(255,255,255,.12);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 576px) {
        .dash-stat-card .stat-value { font-size: 22px; }
        .dash-amount-card .amount-value { font-size: 20px; }
    }
</style>
@endpush

@section('content')

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="dash-welcome">
        <div>
            <h4>Welcome back, {{ $user->name ?? 'Admin' }} 👋</h4>
            <p>Here's what's happening with your business today.</p>
        </div>
        <div class="date-chip">
            <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('d M, Y') }}
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: KEY COUNTS                                   --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <p class="dash-section-header"><i class="fas fa-th me-2"></i>Overview</p>
    <div class="row g-3 mb-4 mt-1">

        {{-- Total Clients --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-stat-card border-blue h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="stat-value">{{ number_format($totalClients) }}</p>
                        <p class="stat-label">Total Clients</p>
                        <span class="stat-badge badge-blue">Active</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Invoices --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-stat-card border-purple h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div>
                        <p class="stat-value">{{ number_format($totalInvoices) }}</p>
                        <p class="stat-label">Total Invoices</p>
                        <span class="stat-badge badge-purple">All Time</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Challans --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-stat-card border-teal h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon icon-teal">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <p class="stat-value">{{ number_format($totalChallans) }}</p>
                        <p class="stat-label">Delivery Challans</p>
                        <span class="stat-badge badge-teal">{{ $challansThisMonth }} this month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Quotations --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-stat-card border-pink h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon icon-pink">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <p class="stat-value">{{ number_format($totalQuotations) }}</p>
                        <p class="stat-label">Quotations</p>
                        <span class="stat-badge badge-teal">{{ $quotationsThisMonth }} this month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 2: FINANCIAL SUMMARY                            --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <p class="dash-section-header"><i class="fas fa-rupee-sign me-2"></i>Financial Summary</p>
    <div class="row g-3 mb-4 mt-1">

        {{-- Total Invoice Value --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-amount-card amount-card-blue h-100">
                <div class="amount-header">
                    <p class="amount-label">Total Invoice Value</p>
                    <p class="amount-value">₹{{ number_format($totalInvoiceAmount, 2) }}</p>
                    <p class="amount-sub"><i class="fas fa-file-invoice me-1"></i>{{ $totalInvoices }} invoices</p>
                </div>
            </div>
        </div>

        {{-- Total Collected --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-amount-card amount-card-green h-100">
                <div class="amount-header">
                    <p class="amount-label">Total Collected</p>
                    <p class="amount-value">₹{{ number_format($totalCollected, 2) }}</p>
                    <p class="amount-sub"><i class="fas fa-check-circle me-1"></i>{{ $paidInvoices }} paid invoices</p>
                </div>
            </div>
        </div>

        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-amount-card amount-card-yellow h-100">
                <div class="amount-header">
                    <p class="amount-label">Total Outstanding</p>
                    <p class="amount-value">₹{{ number_format($totalOutstanding, 2) }}</p>
                    <p class="amount-sub"><i class="fas fa-clock me-1"></i>{{ $pendingInvoices + $partialInvoices }} unpaid</p>
                </div>
            </div>
        </div>

        {{-- Overdue --}}
        <div class="col-xl-3 col-md-6">
            <div class="card dash-amount-card amount-card-red h-100">
                <div class="amount-header">
                    <p class="amount-label">Overdue Amount</p>
                    <p class="amount-value">₹{{ number_format($totalOverdueAmount, 2) }}</p>
                    <p class="amount-sub"><i class="fas fa-exclamation-circle me-1"></i>{{ $overdueInvoices }} overdue invoices</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 3: INVOICE BREAKDOWN + DELIVERY CHALLAN VALUE  --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Invoice Status Breakdown --}}
        <div class="col-xl-5 col-md-12">
            <div class="card dash-activity-card h-100">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-chart-pie icon-purple" style="background:#f5f3ff;"></i>
                        Invoice Status Breakdown
                    </h6>
                </div>
                <div class="card-body pb-2">
                    <ul class="invoice-breakdown">
                        <li>
                            <span class="ib-label">
                                <span class="ib-dot" style="background:#16a34a;"></span>
                                Paid Invoices
                            </span>
                            <span class="ib-count">{{ $paidInvoices }}</span>
                        </li>
                        <li>
                            <span class="ib-label">
                                <span class="ib-dot" style="background:#d97706;"></span>
                                Partial Paid
                            </span>
                            <span class="ib-count">{{ $partialInvoices }}</span>
                        </li>
                        <li>
                            <span class="ib-label">
                                <span class="ib-dot" style="background:#6b7280;"></span>
                                Pending
                            </span>
                            <span class="ib-count">{{ $pendingInvoices }}</span>
                        </li>
                        <li>
                            <span class="ib-label">
                                <span class="ib-dot" style="background:#dc2626;"></span>
                                Overdue
                            </span>
                            <span class="ib-count">{{ $overdueInvoices }}</span>
                        </li>
                        <li style="border-top: 2px solid #e5e7eb; margin-top: 4px; padding-top: 14px;">
                            <span class="ib-label" style="font-weight:700; color:#1a1a2e;">
                                <span class="ib-dot" style="background:#2563eb;"></span>
                                Total Invoices
                            </span>
                            <span class="ib-count" style="color:#2563eb;">{{ $totalInvoices }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Invoice Status Count Cards --}}
        <div class="col-xl-7 col-md-12">
            <div class="row g-3 h-100">

                <div class="col-6">
                    <div class="card dash-stat-card border-green h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon icon-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="stat-value">{{ $paidInvoices }}</p>
                                <p class="stat-label">Paid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card dash-stat-card border-yellow h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon icon-yellow">
                                <i class="fas fa-adjust"></i>
                            </div>
                            <div>
                                <p class="stat-value">{{ $partialInvoices }}</p>
                                <p class="stat-label">Partial Paid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card dash-stat-card border-gray h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon icon-gray">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div>
                                <p class="stat-value">{{ $pendingInvoices }}</p>
                                <p class="stat-label">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card dash-stat-card border-red h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon icon-red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <p class="stat-value">{{ $overdueInvoices }}</p>
                                <p class="stat-label">Overdue</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 4: RECENT ACTIVITY TABLES                       --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <p class="dash-section-header"><i class="fas fa-history me-2"></i>Recent Activity</p>
    <div class="row g-3 mb-4 mt-1">

        {{-- Recent Invoices --}}
        <div class="col-xl-4 col-md-12">
            <div class="card dash-activity-card h-100">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-file-invoice icon-purple" style="background:#f5f3ff;"></i>
                        Recent Invoices
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.edit-invoice', $invoice->id) }}"
                                           class="text-decoration-none fw-600" style="color:#2563eb;">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-truncate" style="max-width:90px;">
                                        {{ $invoice->getClient->name ?? 'N/A' }}
                                    </td>
                                    <td class="fw-600">₹{{ number_format($invoice->grand_total, 0) }}</td>
                                    <td>
                                        @php
                                            $sc = match($invoice->status) {
                                                'paid'         => 'status-paid',
                                                'partial_paid' => 'status-partial',
                                                'overdue'      => 'status-overdue',
                                                default        => 'status-pending',
                                            };
                                        @endphp
                                        <span class="status-pill {{ $sc }}">
                                            {{ ucfirst(str_replace('_',' ',$invoice->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No invoices yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.all-invoices') }}" class="view-all-link">
                    View All Invoices <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Recent Challans --}}
        <div class="col-xl-4 col-md-12">
            <div class="card dash-activity-card h-100">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-truck icon-teal" style="background:#ecfeff;"></i>
                        Recent Delivery Challans
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Challan #</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentChallans as $challan)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.edit-delivery-challan', $challan->id) }}"
                                           class="text-decoration-none fw-600" style="color:#0891b2;">
                                            {{ $challan->challan_number }}
                                        </a>
                                    </td>
                                    <td class="text-truncate" style="max-width:90px;">
                                        {{ $challan->client->name ?? 'N/A' }}
                                    </td>
                                    <td class="fw-600">₹{{ number_format($challan->total_amount, 0) }}</td>
                                    <td>{{ $challan->challan_date->format('d M') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No challans yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.all-challans') }}" class="view-all-link">
                    View All Challans <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Recent Quotations --}}
        <div class="col-xl-4 col-md-12">
            <div class="card dash-activity-card h-100">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-file-alt icon-pink" style="background:#fdf2f8;"></i>
                        Recent Quotations
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Attention</th>
                                <th>For</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentQuotations as $quotation)
                                <tr>
                                    <td class="text-truncate" style="max-width:80px;">
                                        <a href="{{ route('admin.edit-quotation', $quotation->id) }}"
                                           class="text-decoration-none fw-600" style="color:#db2777;">
                                            {{ $quotation->client->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="text-truncate" style="max-width:80px;">
                                        {{ $quotation->attention ?? '—' }}
                                    </td>
                                    <td class="text-truncate" style="max-width:80px;">
                                        {{ $quotation->quotation_for ?? '—' }}
                                    </td>
                                    <td>{{ $quotation->date->format('d M') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No quotations yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.all-quotations') }}" class="view-all-link">
                    View All Quotations <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 5: QUICK ACTIONS                                --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <p class="dash-section-header"><i class="fas fa-bolt me-2"></i>Quick Actions</p>
    <div class="row g-3 mt-1 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.add-invoice') }}" class="text-decoration-none">
                <div class="card dash-stat-card border-purple h-100" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon icon-purple">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <p class="stat-value" style="font-size:16px;">Create Invoice</p>
                            <p class="stat-label">New invoice</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.add-delivery-challan') }}" class="text-decoration-none">
                <div class="card dash-stat-card border-teal h-100" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon icon-teal">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <div>
                            <p class="stat-value" style="font-size:16px;">Create Challan</p>
                            <p class="stat-label">New delivery challan</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.add-quotation') }}" class="text-decoration-none">
                <div class="card dash-stat-card border-pink h-100" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon icon-pink">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <p class="stat-value" style="font-size:16px;">Create Quotation</p>
                            <p class="stat-label">New quotation</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.all-invoices') }}?status=overdue" class="text-decoration-none">
                <div class="card dash-stat-card border-red h-100" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon icon-red">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <p class="stat-value" style="font-size:16px;">Overdue Invoices</p>
                            <p class="stat-label">{{ $overdueInvoices }} require attention</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

@endsection