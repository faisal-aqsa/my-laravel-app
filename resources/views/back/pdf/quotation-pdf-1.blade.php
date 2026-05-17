<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Quotation #{{ $quotation->quotation_number }}</title>
</head>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        width: 100%;
        background: #ffffff;
        font-family: Arial, sans-serif;
        color: #2d3743;
        font-size: 13px;
    }

    .page {
        width: 100%;
        min-height: 100vh;
        padding: 0 0 90px 0;
    }

    /* =============================================
       TOP ACCENT LINE
    ============================================= */
    .top-accent {
        height: 5px;
        background: #ffbd59;
        width: 100%;
    }

    /* =============================================
       HEADER SECTION
       Logo top-right, title + meta top-left
    ============================================= */
    .header {
        display: table;
        width: 100%;
        padding: 30px 40px 24px 40px;
        border-bottom: 1px solid #e8ecf0;
    }

    .header-left {
        display: table-cell;
        vertical-align: top;
        width: 60%;
    }

    .header-right {
        display: table-cell;
        vertical-align: top;
        width: 40%;
        text-align: right;
    }

    /* Title row */
    .quotation-heading {
        font-size: 30px;
        font-weight: 900;
        color: #2d3743;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 4px;
    }

    .status-badge {
        display: inline-block;
        background: #ffbd59;
        color: #2d3743;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        vertical-align: middle;
        margin-left: 10px;
        position: relative;
        top: -3px;
    }

    /* Meta rows */
    .meta-table {
        margin-top: 14px;
        border-collapse: collapse;
    }

    .meta-table td {
        padding: 4px 0;
        font-size: 13px;
        vertical-align: top;
    }

    .meta-key {
        color: #9aa5b0;
        width: 130px;
        font-weight: 500;
    }

    .meta-val {
        color: #2d3743;
        font-weight: 700;
    }

    /* Logo */
    .logo-img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        display: inline-block;
    }

    .logo-company {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 3px;
        color: #2d3743;
        text-transform: uppercase;
        margin-top: 6px;
        text-align: right;
    }

    .logo-tagline {
        font-size: 8px;
        letter-spacing: 2px;
        color: #ffbd59;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 2px;
        text-align: right;
    }

    .logo-recycle {
        font-size: 9px;
        color: #b0bac5;
        margin-top: 2px;
        text-align: right;
    }

    /* =============================================
       FROM / FOR BOXES
    ============================================= */
    .boxes-wrap {
        display: table;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        padding: 24px 40px;
        border-bottom: 1px solid #e8ecf0;
    }

    .box-cell {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }

    .box-cell:first-child {
        padding-right: 12px;
    }

    .box-cell:last-child {
        padding-left: 12px;
    }

    .info-box {
        background: #f7f9fb;
        border: 1px solid #e4e9ee;
        border-top: 3px solid #ffbd59;
        padding: 16px 18px;
        height: 100%;
    }

    .box-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 10px;
    }

    .box-name {
        font-size: 14px;
        font-weight: 800;
        color: #2d3743;
        margin-bottom: 5px;
    }

    .box-detail {
        font-size: 12.5px;
        color: #666;
        line-height: 1.65;
    }

    .box-detail strong {
        color: #2d3743;
    }

    /* =============================================
       SUPPLY INFO ROW
    ============================================= */
    .supply-row {
        display: table;
        width: 100%;
        padding: 10px 40px;
        background: #fafbfc;
        border-bottom: 1px solid #e8ecf0;
    }

    .supply-left {
        display: table-cell;
        width: 50%;
        font-size: 12.5px;
        color: #555;
        vertical-align: middle;
    }

    .supply-right {
        display: table-cell;
        width: 50%;
        text-align: right;
        font-size: 12.5px;
        color: #555;
        vertical-align: middle;
    }

    .supply-left strong,
    .supply-right strong {
        color: #2d3743;
    }

    /* =============================================
       ITEMS TABLE
    ============================================= */
    .table-wrap {
        padding: 0 40px;
        margin-top: 24px;
    }

    .sec-title {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 12px;
        padding-left: 10px;
        border-left: 3px solid #ffbd59;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead tr th {
        background: #2d3743;
        color: #ffbd59;
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 12px 14px;
        text-align: left;
    }

    .items-table thead tr th.center { text-align: center; }
    .items-table thead tr th.right  { text-align: right; padding-right: 16px; }

    .items-table tbody tr {
        border-bottom: 1px solid #edf0f4;
    }

    .items-table tbody tr:nth-child(even) { background: #f7f9fb; }
    .items-table tbody tr:nth-child(odd)  { background: #ffffff; }

    .items-table tbody td {
        padding: 13px 14px;
        font-size: 13px;
        color: #2d3743;
        vertical-align: middle;
    }

    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.right  { text-align: right; padding-right: 16px; font-weight: 700; }
    .items-table tbody td.num    { color: #aab0b8; font-weight: 700; font-size: 11px; width: 36px; }

    .hsn-small {
        font-size: 10.5px;
        color: #9aa5b0;
        display: block;
        margin-top: 2px;
    }

    .gsm-pill {
        display: inline-block;
        background: #edf1f6;
        border: 1px solid #dde3ea;
        color: #2d3743;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* =============================================
       BOTTOM: INCLUSIONS LEFT + SUMMARY RIGHT
    ============================================= */
    .bottom-section {
        display: table;
        width: 100%;
        padding: 24px 40px 0 40px;
        border-collapse: collapse;
    }

    .bottom-left {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 20px;
    }

    .bottom-right {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-left: 20px;
    }

    /* Inclusions */
    .inc-card {
        border: 1px solid #e0e5ea;
        border-top: 3px solid #ffbd59;
    }

    .inc-head {
        background: #f7f9fb;
        border-bottom: 1px solid #e8ecf0;
        padding: 9px 14px;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
    }

    .inc-row {
        display: table;
        width: 100%;
        padding: 9px 14px;
        border-bottom: 1px solid #f0f3f7;
    }

    .inc-row:last-child { border-bottom: none; }

    .inc-lbl {
        display: table-cell;
        font-size: 12.5px;
        color: #555;
        vertical-align: middle;
    }

    .inc-val {
        display: table-cell;
        text-align: right;
        vertical-align: middle;
    }

    .tag-yes {
        display: inline-block;
        background: #2d3743;
        color: #ffbd59;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 10px;
    }

    .tag-no {
        font-size: 14px;
        color: #d5dbe0;
        font-weight: 700;
    }

    /* Summary table */
    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table tr td {
        padding: 9px 0;
        font-size: 13px;
        color: #555;
        border-bottom: 1px solid #f0f3f7;
        vertical-align: middle;
    }

    .summary-table tr:last-child td {
        border-bottom: none;
    }

    .summary-table tr td:last-child {
        text-align: right;
        font-weight: 700;
        color: #2d3743;
    }

    .summary-total-row td {
        font-size: 15px !important;
        font-weight: 900 !important;
        color: #2d3743 !important;
        padding-top: 13px !important;
        border-top: 2px solid #2d3743 !important;
        border-bottom: 2px solid #2d3743 !important;
    }

    /* =============================================
       NOTES & TERMS
    ============================================= */
    .notes-section {
        padding: 20px 40px 0 40px;
        display: table;
        width: 100%;
    }

    .notes-left {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 20px;
    }

    .notes-right {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-left: 20px;
    }

    .notes-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #ffbd59;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .notes-body {
        font-size: 12.5px;
        color: #666;
        line-height: 1.7;
    }

    /* =============================================
       SIGNATURES
    ============================================= */
    .sig-wrap {
        display: table;
        width: 100%;
        margin-top: 40px;
        padding: 0 40px;
    }

    .sig-l {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-right: 60px;
    }

    .sig-r {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-left: 60px;
        text-align: right;
    }

    .sig-spacer { height: 45px; }

    .sig-line {
        border-top: 1.5px solid #2d3743;
        padding-top: 8px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #2d3743;
    }

    .sig-line.gold-top { border-top-color: #ffbd59; }

    .sig-sub {
        font-size: 8.5px;
        color: #c0c8d0;
        margin-top: 2px;
        font-weight: 400;
    }

    /* =============================================
       FOOTER
    ============================================= */
    .page-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #2d3743;
        border-top: 4px solid #ffbd59;
    }

    .footer-table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-td {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        padding: 12px 10px;
        font-size: 12px;
        color: rgba(255,255,255,0.7);
        border-right: 1px solid rgba(255,189,89,0.15);
        width: 33.33%;
    }

    .footer-td:last-child { border-right: none; }
    .footer-td strong { color: #ffbd59; }
</style>

<body>
<div class="page">

    <div class="top-accent"></div>

    {{-- ===================== HEADER ===================== --}}
    @php
        $settings = \App\Models\Setting::first();
        $logoPath = public_path('images/black-logo.png');
        $logoSrc  = '';
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header">
        <div class="header-left">
            <div>
                <span class="quotation-heading">Quotation</span>
                <span class="status-badge">Created</span>
            </div>

            <table class="meta-table">
                <tr>
                    <td class="meta-key">Quotation No</td>
                    <td class="meta-val">{{ $quotation->quotation_number }}</td>
                </tr>
                <tr>
                    <td class="meta-key">Quotation Date</td>
                    <td class="meta-val">{{ $quotation->date->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-key">Valid Till Date</td>
                    <td class="meta-val">
                        {{ isset($quotation->valid_until) ? $quotation->valid_until->format('M d, Y') : 'On Request' }}
                    </td>
                </tr>
                @if($quotation->attention)
                <tr>
                    <td class="meta-key">Attention</td>
                    <td class="meta-val">{{ $quotation->attention }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="header-right">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Boxmaker Logo" class="logo-img">
            @endif
            <div class="logo-company">BOXMAKER</div>
            <div class="logo-tagline">Packaging &amp; Printing</div>
            <div class="logo-recycle">♻ We Generally Recycle</div>
        </div>
    </div>

    {{-- ===================== FROM / FOR BOXES ===================== --}}
    <div class="boxes-wrap">
        <div class="box-cell">
            <div class="info-box">
                <div class="box-title">Quotation From</div>
                <div class="box-name">BOXMAKER</div>
                <div class="box-detail">
                    {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai - 400092' }}<br>
                    @if($settings->gst_no)
                        <strong>GSTIN:</strong> {{ $settings->gst_no }}<br>
                    @endif
                    @if($settings->phone)
                        <strong>Phone:</strong> {{ $settings->phone }}<br>
                    @endif
                    @if($settings->email)
                        <strong>Email:</strong> {{ $settings->email }}
                    @endif
                </div>
            </div>
        </div>

        <div class="box-cell">
            <div class="info-box">
                <div class="box-title">Quotation For</div>
                <div class="box-name">{{ $quotation->client->name ?? 'N/A' }}</div>
                <div class="box-detail">
                    @if($quotation->client->factory_address ?? $quotation->client->address ?? false)
                        {{ $quotation->client->factory_address ?? $quotation->client->address }}<br>
                    @endif
                    @if($quotation->client->gst_no ?? false)
                        <strong>GSTIN:</strong> {{ $quotation->client->gst_no }}<br>
                    @endif
                    @if($quotation->client->phone ?? false)
                        <strong>Phone:</strong> {{ $quotation->client->phone }}<br>
                    @endif
                    @if($quotation->client->email ?? false)
                        <strong>Email:</strong> {{ $quotation->client->email }}
                    @endif
                    @if($quotation->quotation_for)
                        <br><strong>For:</strong> {{ $quotation->quotation_for }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== ITEMS TABLE ===================== --}}
    <div class="table-wrap">
        <div class="sec-title">Items &amp; Pricing</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:36px;">&nbsp;</th>
                    <th>Product, Material &amp; Size</th>
                    <th class="center" style="width:100px;">GSM</th>
                    <th class="center" style="width:90px;">Quantity</th>
                    <th class="right" style="width:130px;">Basic Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotation->items as $index => $item)
                <tr>
                    <td class="num">{{ $index + 1 }}.</td>
                    <td>
                        <span style="font-weight:600;">{{ $item->particular }}</span>
                    </td>
                    <td class="center">
                        @if($item->gsm)
                            <span class="gsm-pill">{{ $item->gsm }}</span>
                        @else
                            <span style="color:#d0d5db;">—</span>
                        @endif
                    </td>
                    <td class="center">—</td>
                    <td class="right">₹{{ number_format($item->base_price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#aaa; padding:28px; font-style:italic;">
                        No items found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===================== BOTTOM: INCLUSIONS + SUMMARY ===================== --}}
    <div class="bottom-section">

        {{-- Inclusions --}}
        <div class="bottom-left">
            <div class="inc-card">
                <div class="inc-head">What's Included</div>
                @php
                    $inclusions = [
                        ['Taxes',            $quotation->is_tax_included],
                        ['Delivery Charges', $quotation->is_delivery_charges_included],
                        ['Printing',         $quotation->is_printing_included],
                        ['Plate & Punch',    $quotation->is_plate_and_punch],
                        ['Lamination',       $quotation->is_lamination],
                    ];
                @endphp
                @foreach($inclusions as [$label, $val])
                <div class="inc-row">
                    <div class="inc-lbl">{{ $label }}</div>
                    <div class="inc-val">
                        @if($val)
                            <span class="tag-yes">✓ Included</span>
                        @else
                            <span class="tag-no">—</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Summary --}}
        <div class="bottom-right">
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td>
                        ₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Taxes</td>
                    <td>{{ $quotation->is_tax_included ? 'Included' : '—' }}</td>
                </tr>
                <tr>
                    <td>Delivery</td>
                    <td>{{ $quotation->is_delivery_charges_included ? 'Included' : '—' }}</td>
                </tr>
                <tr class="summary-total-row">
                    <td>Total (INR)</td>
                    <td>₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
                </tr>
            </table>
        </div>

    </div>

    {{-- ===================== NOTES & TERMS ===================== --}}
    <div class="notes-section" style="margin-top:20px;">
        @if($quotation->notes)
        <div class="notes-left">
            <div class="notes-title">Additional Notes</div>
            <div class="notes-body">{{ $quotation->notes }}</div>
        </div>
        @endif
        <div class="{{ $quotation->notes ? 'notes-right' : 'notes-left' }}">
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-body">
                1. Prices are subject to change without prior notice.<br>
                2. Delivery as per mutual agreement.<br>
                3. This quotation is valid for the period mentioned above.
            </div>
        </div>
    </div>

    {{-- ===================== SIGNATURES ===================== --}}
    <div class="sig-wrap">
        <div class="sig-l">
            <div class="sig-spacer"></div>
            <div class="sig-line">
                Client Acknowledgement
                <div class="sig-sub">Signature &amp; Company Stamp</div>
            </div>
        </div>
        <div class="sig-r">
            <div class="sig-spacer"></div>
            <div class="sig-line gold-top">
                For BOXMAKER
                <div class="sig-sub">Authorised Signatory</div>
            </div>
        </div>
    </div>

    {{-- ===================== FOOTER ===================== --}}
    <div class="page-footer">
        <table class="footer-table">
            <tr>
                <td class="footer-td"><strong>{{ $settings->phone ?? '+91 9820006001' }}</strong></td>
                <td class="footer-td">{{ $settings->email ?? 'boxmaker@myyahoo.com' }}</td>
                <td class="footer-td"><strong>{{ $settings->website_url ?? 'www.myboxmaker.com' }}</strong></td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>