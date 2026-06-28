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
        height: 100%;
        background: #ffffff;
        font-family: Arial, Helvetica, sans-serif;
        color: #2d3743;
        font-size: 12px;
        -webkit-print-color-adjust: exact;
    }

    /* =============================================
       PAGE AS TABLE - FORCES BOTTOM ALIGNMENT
    ============================================= */
    .page {
        width: 100%;
        height: 100vh;
        display: table;
        table-layout: fixed;
        padding-bottom: 60px; /* space for footer */
    }

    .page-row {
        display: table-row;
    }

    .page-cell {
        display: table-cell;
        vertical-align: top;
    }

    .page-cell-bottom {
        display: table-cell;
        vertical-align: bottom;
    }

    /* =============================================
       HEADER - NEW STYLE WITH LOGO LEFT, CONTENT RIGHT
    ============================================= */
    .header {
        background: #2d3743;
        padding: 25px 40px 25px 40px;
        width: 100%;
    }

    .header-inner {
        display: flex;
        align-items: flex-start;
        gap: 30px;
        width: 100%;
    }

    /* Logo on left with white background */
    .header-logo {
        flex-shrink: 0;
        background: #ffffff;
        border-radius: 12px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        min-height: 90px;
    }

    .logo-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        display: block;
    }

    /* Right side - company info */
    .header-info {
        flex: 1;
        text-align: right;
        padding-top: 5px;
    }

    .company-name {
        font-size: 22px;
        font-weight: 900;
        letter-spacing: 3px;
        color: #ffffff;
        text-transform: uppercase;
        line-height: 1.1;
    }

    .company-tagline {
        font-size: 9px;
        letter-spacing: 3px;
        color: #ffbd59;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 2px;
    }

    .company-recycle {
        font-size: 8px;
        color: rgba(255,255,255,0.45);
        margin-top: 2px;
        letter-spacing: 0.5px;
    }

    .company-address {
        margin-top: 8px;
        font-size: 10px;
        color: rgba(255,255,255,0.7);
        line-height: 1.5;
    }

    .company-address strong {
        color: #ffbd59;
        font-weight: 700;
    }

    /* Contact info row - Phone, Email, Web */
    .contact-info-row {
        margin-top: 10px;
        display: flex;
        justify-content: flex-end;
        gap: 25px;
        flex-wrap: wrap;
    }

    .contact-item {
        text-align: left;
    }

    .contact-item .label {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ffbd59;
        display: block;
        margin-bottom: 2px;
    }

    .contact-item .value {
        font-size: 10px;
        color: rgba(255,255,255,0.85);
        font-weight: 600;
        white-space: nowrap;
    }

    /* =============================================
       TITLE + META + RECIPIENT
    ============================================= */
    .title-wrap { padding: 18px 40px 0 40px; }
    .title-table { width: 100%; border-collapse: collapse; }
    .title-table td { vertical-align: top; }
    .tt-left  { width: 56%; padding-right: 25px; }
    .tt-right { width: 44%; text-align: right; }

    .big-quotation {
        font-size: 30px;
        font-weight: 900;
        letter-spacing: 2px;
        color: #2d3743;
        line-height: 1;
        margin-bottom: 8px;
    }

    .for-label {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 4px;
    }
    .for-name {
        font-size: 14px;
        font-weight: 800;
        color: #2d3743;
        margin-bottom: 3px;
        line-height: 1.2;
    }
    .for-detail {
        font-size: 10px;
        color: #6b7682;
        line-height: 1.4;
    }
    .for-detail strong { color: #2d3743; }

    .meta-table { border-collapse: collapse; display: inline-table; text-align: left; }
    .meta-table td { padding: 3px 0; font-size: 11px; vertical-align: top; }
    .meta-key { color: #9aa5b0; font-weight: 600; white-space: nowrap; }
    .meta-gap { width: 20px; }
    .meta-val { color: #2d3743; font-weight: 800; white-space: nowrap; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area {
        padding: 0 40px;
        width: 100%;
    }

    /* =============================================
       ITEMS TABLE
    ============================================= */
    .table-wrap { 
        padding: 15px 0 0 0;
    }

    .sec-title {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 8px;
        padding-left: 8px;
        border-left: 3px solid #ffbd59;
    }

    .items-table { width: 100%; border-collapse: collapse; }

    .items-table thead th {
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 8px 10px;
        text-align: left;
    }
    .th-yellow { background: #ffbd59; color: #2d3743; }
    .th-dark   { background: #2d3743; color: #ffbd59; }
    .th-dark.center { text-align: center; }
    .th-dark.right  { text-align: right; padding-right: 12px; }

    .items-table tbody tr:nth-child(even) { background: #f5f7f9; }
    .items-table tbody tr:nth-child(odd)  { background: #ffffff; }
    .items-table tbody td {
        padding: 8px 10px;
        font-size: 11px;
        color: #2d3743;
        vertical-align: middle;
        border-bottom: 1px solid #eceff3;
    }
    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.right  { text-align: right; padding-right: 12px; font-weight: 800; }
    .items-table tbody td.num    { color: #aeb6bf; font-weight: 800; font-size: 10px; width: 35px; }

    .gsm-pill {
        display: inline-block;
        background: #ffffff;
        border: 1px solid #d9dee5;
        color: #2d3743;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }

    /* =============================================
       SUMMARY
    ============================================= */
    .summary-wrap { 
        padding: 10px 0 0 0;
        text-align: right;
    }
    .summary-table { 
        width: 300px; 
        border-collapse: collapse; 
        display: inline-table; 
        text-align: left;
    }
    .summary-table td {
        padding: 4px 0;
        font-size: 11px;
        color: #6b7682;
        border-bottom: 1px solid #f0f3f7;
        vertical-align: middle;
    }
    .summary-table td:last-child {
        text-align: right;
        font-weight: 800;
        color: #2d3743;
        padding-left: 30px;
    }

    .grand-total-wrap { 
        padding: 6px 0 0 0;
        text-align: right;
    }
    .grand-total-bar {
        display: inline-table;
        width: 300px;
        background: #ffbd59;
        border-collapse: collapse;
        border-radius: 4px;
    }
    .grand-total-bar td {
        padding: 8px 14px;
        color: #2d3743;
        vertical-align: middle;
    }
    .gt-label {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .gt-value {
        text-align: right;
        font-size: 15px;
        font-weight: 900;
    }

    /* =============================================
       CLOSING SECTION - BOTTOM ALIGNED VIA TABLE
    ============================================= */
    .closing-wrap {
        padding: 8px 40px 10px 40px;
        background: #ffffff;
        width: 100%;
    }
    .closing-table { 
        width: 100%; 
        border-collapse: collapse;
    }
    .closing-table td { 
        vertical-align: bottom;
    }
    .cl-left  { 
        width: 58%; 
        padding-right: 25px;
        vertical-align: bottom;
    }
    .cl-right { 
        width: 42%; 
        text-align: right;
        vertical-align: bottom;
    }

    .thanks {
        font-size: 16px;
        font-weight: 900;
        color: #2d3743;
        margin-bottom: 4px;
    }

    .notes-title {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 2px;
    }
    .notes-body {
        font-size: 11px;
        color: #6b7682;
        line-height: 1.4;
    }

    .sig-block { 
        display: inline-block; 
        width: 200px; 
        text-align: center;
        vertical-align: bottom;
    }
    .sig-line {
        border-top: 2px solid #ffbd59;
        padding-top: 6px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #2d3743;
    }
    .sig-sub {
        font-size: 9px;
        color: #aeb6bf;
        margin-top: 1px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* =============================================
       FIXED FOOTER BAR
    ============================================= */
    .page-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background: #2d3743;
        border-top: 3px solid #ffbd59;
        z-index: 999;
        height: 60px;
    }
    .footer-table {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .footer-td {
        text-align: center;
        vertical-align: middle;
        padding: 6px 8px;
        font-size: 12px;
        color: rgba(255,255,255,0.72);
        border-right: 1px solid rgba(255,189,89,0.15);
        width: 33.33%;
        word-wrap: break-word;
    }
    .footer-td:last-child { border-right: none; }
    .footer-td strong { color: #ffbd59; font-weight: 700; }

    @media print {
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .page {
            height: 100%;
        }
    }
</style>

<body>
<div class="page">

    {{-- ============ HEADER ROW ============ --}}
    <div class="page-row">
        <div class="page-cell">
            @php
                $settings = \App\Models\Setting::first();
                $logoPath = public_path('images/black-logo.png');
                $logoSrc  = '';
                if (file_exists($logoPath)) {
                    $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                }
            @endphp

            {{-- NEW HEADER WITH LOGO LEFT, INFO RIGHT --}}
            <div class="header">
                <div class="header-inner">
                    <!-- Logo on left with white bg -->
                    <div class="header-logo">
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" alt="Boxmaker Logo" class="logo-img">
                        @else
                            <svg width="80" height="80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="10" y="10" width="80" height="80" rx="12" fill="#2d3743" stroke="#ffbd59" stroke-width="4"/>
                                <text x="50" y="58" font-family="Arial, sans-serif" font-size="28" font-weight="900" fill="#ffbd59" text-anchor="middle">B</text>
                                <text x="50" y="78" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#ffffff" text-anchor="middle">BOX</text>
                            </svg>
                        @endif
                    </div>

                    <!-- Right side - company info -->
                    <div class="header-info">
                        <div class="company-name">Boxmaker</div>
                        <div class="company-tagline">Packaging &amp; Printing</div>
                        <div class="company-recycle">&#9851; We Generally Recycle</div>

                        <div class="company-address">
                            {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai - 400092' }}<br>
                            <strong>GSTIN:</strong> {{ $settings->gst_no ?? '27ABDFB7083N1ZY' }}
                        </div>

                        <div class="contact-info-row">
                            <div class="contact-item">
                                <span class="label">Phone</span>
                                <span class="value">{{ $settings->phone ?? '+91 9820006001' }}</span>
                            </div>
                            <div class="contact-item">
                                <span class="label">Email</span>
                                <span class="value">{{ $settings->email ?? 'boxmaker@myyahoo.com' }}</span>
                            </div>
                            <div class="contact-item">
                                <span class="label">Web</span>
                                <span class="value">{{ $settings->website_url ?? 'www.myboxmaker.com' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ TITLE / META / RECIPIENT ============ --}}
            <div class="title-wrap">
                <table class="title-table">
                    <tr>
                        <td class="tt-left">
                            <div class="for-label">Quotation For</div>
                            <div class="for-name">{{ $quotation->client->name ?? 'N/A' }}</div>
                            <div class="for-detail">
                                @if($quotation->client->factory_address ?? $quotation->client->address ?? false)
                                    {{ $quotation->client->factory_address ?? $quotation->client->address }}<br>
                                @endif
                                @if($quotation->client->gst_no ?? false)<strong>GSTIN:</strong> {{ $quotation->client->gst_no }}<br>@endif
                                @if($quotation->client->phone ?? false)<strong>Phone:</strong> {{ $quotation->client->phone }}<br>@endif
                                @if($quotation->client->email ?? false)<strong>Email:</strong> {{ $quotation->client->email }}<br>@endif
                                @if($quotation->quotation_for ?? false)<strong>For:</strong> {{ $quotation->quotation_for }}@endif
                            </div>
                        </td>
                        <td class="tt-right">
                            <div class="big-quotation">QUOTATION</div>
                            <table class="meta-table">
                                <tr>
                                    <td class="meta-key">Quotation No</td>
                                    <td class="meta-gap"></td>
                                    <td class="meta-val">{{ $quotation->quotation_number }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-key">Quotation Date</td>
                                    <td class="meta-gap"></td>
                                    <td class="meta-val">{{ $quotation->date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-key">Valid Till Date</td>
                                    <td class="meta-gap"></td>
                                    <td class="meta-val">{{ isset($quotation->valid_until) ? $quotation->valid_until->format('M d, Y') : 'On Request' }}</td>
                                </tr>
                                @if($quotation->attention)
                                <tr>
                                    <td class="meta-key">Attention</td>
                                    <td class="meta-gap"></td>
                                    <td class="meta-val">{{ $quotation->attention }}</td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ CONTENT ROW - TAKES REMAINING SPACE ============ --}}
    <div class="page-row" style="height: 100%;">
        <div class="page-cell" style="vertical-align: top;">

            {{-- ============ ITEMS TABLE ============ --}}
            <div class="content-area">
                <div class="table-wrap">
                    <div class="sec-title">Items &amp; Pricing</div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th class="th-yellow" style="width:35px;">&nbsp;</th>
                                <th class="th-yellow">Product, Material &amp; Size</th>
                                <th class="th-dark center" style="width:90px;">GSM</th>
                                <th class="th-dark right" style="width:130px;">Basic Price (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quotation->items as $index => $item)
                            <tr>
                                <td class="num">{{ $index + 1 }}.</td>
                                <td style="font-weight:600;">{{ $item->particular }}</td>
                                <td class="center">
                                    @if($item->gsm)
                                        <span class="gsm-pill">{{ $item->gsm }}</span>
                                    @else
                                        <span style="color:#d0d5db;">—</span>
                                    @endif
                                </td>
                                <td class="right">₹{{ number_format($item->base_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#aaa;padding:20px;font-style:italic;">No items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ============ SUMMARY ============ --}}
                <div class="summary-wrap">
                    <table class="summary-table">
                        <tr>
                            <td>Subtotal</td>
                            <td>₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Taxes</td>
                            <td>{{ $quotation->is_tax_included ? 'Included' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Delivery Charges</td>
                            <td>{{ $quotation->is_delivery_charges_included ? 'Included' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Printing</td>
                            <td>{{ $quotation->is_printing_included ? 'Included' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Plate &amp; Punch</td>
                            <td>{{ $quotation->is_plate_and_punch ? 'Included' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Lamination</td>
                            <td>{{ $quotation->is_lamination ? 'Included' : '—' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- ============ GRAND TOTAL BAR ============ --}}
                <div class="grand-total-wrap">
                    <table class="grand-total-bar">
                        <tr>
                            <td class="gt-label">Total (INR)</td>
                            <td class="gt-value">₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ============ CLOSING ROW - BOTTOM ALIGNED ============ --}}
    <div class="page-row">
        <div class="page-cell-bottom">
            <div class="closing-wrap">
                <table class="closing-table">
                    <tr>
                        <td class="cl-left">
                            <div class="thanks">Thank you for your business!</div>
                            @if($quotation->notes)
                            <div class="notes-title">Additional Notes</div>
                            <div class="notes-body">{{ $quotation->notes }}</div>
                            @endif
                        </td>
                        <td class="cl-right">
                            <div class="sig-block">
                                <div class="sig-line">
                                    For Boxmaker
                                    <div class="sig-sub">Authorised Signatory</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ FIXED FOOTER ============ --}}
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