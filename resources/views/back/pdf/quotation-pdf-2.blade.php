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
        padding-bottom: 150px;   /* clears the fixed closing block (~90px) + footer (60px) */
    }

    .page-row { display: table-row; }

    .page-cell {
        display: table-cell;
        vertical-align: top;
    }

    .page-cell-bottom {
        display: table-cell;
        vertical-align: bottom;
    }

    /* =============================================
       HEADER · logo left (white) · info panel right
    ============================================= */
    .header {
        background: #ffffff;
        padding: 22px 40px 6px 40px;
        width: 100%;
    }

    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { vertical-align: middle; }

    /* LEFT: white rounded logo box (no border) */
    .header-logo-cell { width: 150px; padding-right: 22px; }

    .logo-box {
        background: #ffffff;
        border-radius: 16px;
        padding: 6px;
        text-align: center;
    }

    .logo-img {
        width: 112px;
        height: 112px;
        object-fit: contain;
        display: inline-block;
    }

    /* RIGHT: dark panel with big left curve.
       Solid colour is the reliable fallback; the gradient is drawn as SVG so it
       renders even when the PDF engine ignores CSS gradients. */
    .brand-panel {
        position: relative;
        overflow: hidden;
        background: #2d3743;
        border-radius: 64px 20px 20px 64px;
        padding: 22px 156px 22px 50px;   /* big right pad reserves room for decorations */
        min-height: 132px;
    }

    /* full-panel gradient drawn via SVG (works without CSS gradient support) */
    .panel-bg { position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1; }
    .panel-bg svg { display: block; width: 100%; height: 100%; }

    /* keep all text above the gradient layer */
    .panel-content { position: relative; z-index: 2; }

    .brand-name {
        font-size: 30px;
        font-weight: 900;
        letter-spacing: 3px;
        color: #ffffff;
        text-transform: uppercase;
        line-height: 1;
    }

    .brand-tagline {
        font-size: 9px;
        letter-spacing: 3px;
        color: #ffbd59;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 4px;
    }

    .brand-recycle {
        font-size: 8px;
        color: rgba(255,255,255,0.45);
        margin-top: 3px;
        letter-spacing: 0.5px;
    }

    .brand-address {
        margin-top: 9px;
        font-size: 9.5px;
        color: rgba(255,255,255,0.72);
        line-height: 1.5;
    }
    .brand-address strong { color: #ffbd59; font-weight: 700; }
    .address h5 {
        font-size: 10px;
    }

    .brand-contacts { margin-top: 11px; }
    .brand-contacts-table { border-collapse: collapse; }
    .brand-contacts-table td { padding-right: 26px; vertical-align: top; }

    .bc-label {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ffbd59;
        display: block;
        margin-bottom: 2px;
    }
    .bc-value {
        font-size: 9.5px;
        color: rgba(255,255,255,0.88);
        font-weight: 600;
        white-space: nowrap;
    }

    /* geometric decoration clipped on the right edge */
    /* .panel-decor {
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 156px;
        z-index: 2;
        pointer-events: none;
    }
    .panel-decor svg { display: block; width: 100%; height: 100%; } */

    .panel-decor {
        position: absolute;
        top: 50%;
        right: 25px;              /* Space from the right edge */
        transform: translateY(-50%);
        width: 40px;              /* Only enough width for the bars */
        height: 80px;             /* Increase overall height */
        z-index: 2;
        pointer-events: none;
    }

    .panel-decor svg {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* =============================================
       TITLE + META + RECIPIENT
    ============================================= */
    .title-wrap { padding: 18px 40px 0 40px; }
    .title-table { width: 100%; border-collapse: collapse; }
    .title-table td { vertical-align: top; }
    .tt-left  { width: 56%; padding-right: 25px; }
    /* .tt-right { width: 44%; text-align: right; } */
    .tt-right {
        width: 44%;
        text-align: right;
        padding-top: 60px; 
    }

    .big-quotation {
        font-size: 30px;
        font-weight: 900;
        letter-spacing: 2px;
        color: #2d3743;
        line-height: 1;
        margin-bottom: 8px;
    }

    .for-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 4px;
    }
    .for-name {
        font-size: 15px;
        font-weight: 800;
        color: #2d3743;
        margin-bottom: 3px;
        line-height: 1.2;
    }
    .for-detail { font-size: 11px; color: #6b7682; line-height: 1.45; }
    .for-detail strong { color: #2d3743; }

    .meta-table { border-collapse: collapse; display: inline-table; text-align: left; }
    .meta-table td { padding: 3px 0; font-size: 12px; vertical-align: top; }
    .meta-key { color: #9aa5b0; font-weight: 600; white-space: nowrap; }
    .meta-gap { width: 20px; }
    .meta-val { color: #2d3743; font-weight: 800; white-space: nowrap; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area { padding: 0 40px; width: 100%; }

    /* =============================================
       ITEMS TABLE
    ============================================= */
    .table-wrap { padding: 15px 0 0 0; }

    .sec-title {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
        margin-top: 20px;
        margin-bottom: 8px;
        padding-left: 8px;
        border-left: 3px solid #ffbd59;
    }

    .items-table { width: 100%; border-collapse: collapse; }

    .items-table thead th {
        font-size: 9px;
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
        font-size: 12px;
        color: #2d3743;
        vertical-align: middle;
        border-bottom: 1px solid #eceff3;
    }
    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.right  { text-align: right; padding-right: 12px; font-weight: 800; }
    .items-table tbody td.num    { color: #aeb6bf; font-weight: 800; font-size: 11px; width: 35px; }

    .gsm-pill {
        display: inline-block;
        background: #ffffff;
        border: 1px solid #d9dee5;
        color: #2d3743;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }

    /* =============================================
       SUMMARY
    ============================================= */
    .summary-wrap { padding: 10px 0 0 0; text-align: right; }
    .summary-table {
        width: 300px;
        border-collapse: collapse;
        display: inline-table;
        text-align: left;
    }
    .summary-table td {
        padding: 4px 0;
        font-size: 12px;
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

    .grand-total-wrap { padding: 6px 0 0 0; text-align: right; }
    .grand-total-bar {
        display: inline-table;
        width: 300px;
        background: #ffbd59;
        border-collapse: collapse;
        border-radius: 4px;
    }
    .grand-total-bar td { padding: 8px 14px; color: #2d3743; vertical-align: middle; }
    .gt-label {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .gt-value { text-align: right; font-size: 15px; font-weight: 900; }

    .order-note {
        width: 100%;
        text-align: center;
        margin-top: 140px;   /* Adjust according to your layout */
        font-size: 14px;
        /* font-weight: bold; */
        color: #2d3743;
        line-height: 1.4;
    }

    /* =============================================
       CLOSING SECTION - BOTTOM ALIGNED
    ============================================= */
    .closing-wrap {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 60px;            /* sits directly on top of the 60px footer */
        background: #ffffff;
        padding: 10px 40px 12px 40px;
        width: 100%;
        z-index: 998;            /* above content, below the footer (999) */
    }
    .closing-table { width: 100%; border-collapse: collapse; }
    .closing-table td { vertical-align: bottom; }
    .cl-left  { width: 58%; padding-right: 25px; vertical-align: bottom; }
    .cl-right { width: 42%; text-align: right; vertical-align: bottom; }

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
    .notes-body { font-size: 11px; color: #6b7682; line-height: 1.4; }

    .sig-block {
        display: inline-block;
        width: 200px;
        text-align: center;
        vertical-align: bottom;
    }
    .sig-img {
        max-width: 130px;
        max-height: 58px;
        object-fit: contain;
        display: block;
        margin: 0 auto 6px auto;
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
        .page-footer { position: fixed; bottom: 0; left: 0; right: 0; }
        .page { height: 100%; }
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

            {{-- HEADER: LOGO LEFT (white) · INFO PANEL RIGHT (gradient) --}}
            <div class="header">
                <table class="header-table">
                    <tr>
                        {{-- LEFT: white rounded logo box --}}
                        <td class="header-logo-cell">
                            <div class="logo-box">
                                @if($logoSrc)
                                    <img src="{{ $logoSrc }}" alt="Boxmaker Logo" class="logo-img">
                                @else
                                    <svg width="104" height="104" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="10" y="10" width="80" height="80" rx="12" fill="#2d3743" stroke="#ffbd59" stroke-width="4"/>
                                        <text x="50" y="58" font-family="Arial, sans-serif" font-size="28" font-weight="900" fill="#ffbd59" text-anchor="middle">B</text>
                                        <text x="50" y="78" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#ffffff" text-anchor="middle">OX</text>
                                    </svg>
                                @endif
                            </div>
                        </td>

                        {{-- RIGHT: dark gradient info panel with big left curve + decorations --}}
                        <td>
                            <div class="brand-panel">

                                {{-- gradient background drawn as SVG (renders where CSS gradients don't) --}}
                                <div class="panel-bg">
                                    <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="panelGrad" x1="0" y1="0" x2="1" y2="1">
                                                <stop offset="0"    stop-color="#232c35"/>
                                                <stop offset="0.55" stop-color="#2d3743"/>
                                                <stop offset="1"    stop-color="#3b4858"/>
                                            </linearGradient>
                                        </defs>
                                        <rect x="0" y="0" width="100" height="100" fill="url(#panelGrad)"/>
                                    </svg>
                                </div>

                                <div class="panel-content">
                                <div class="brand-name">QUOTATION</div>
                                {{-- <div class="brand-tagline">Packaging &amp; Printing</div>
                                <div class="brand-recycle">&#9851; We Generally Recycle</div> --}}

                                <div class="brand-address">
                                    <div class="address">
                                        <h5>{{ $settings->address ?? 'Office : 307, Sai Janak Classic, Near Flyover, Devidas lane, Borivali West. Mumbai - 400092' }}</h5>
                                    </div>
                                    <br>
                                    <strong>GSTIN:</strong> {{ $settings->gst_no ?? '27ABDFB7083N1ZY' }}
                                </div>

                                <div class="brand-contacts">
                                    <table class="brand-contacts-table">
                                        <tr>
                                            <td>
                                                <span class="bc-label">Phone</span>
                                                <span class="bc-value">{{ $settings->phone ?? '+91 9820006001' }}</span>
                                            </td>
                                            <td>
                                                <span class="bc-label">Email</span>
                                                <span class="bc-value">{{ $settings->email ?? 'boxmaker@myyahoo.com' }}</span>
                                            </td>
                                            <td>
                                                <span class="bc-label">Web</span>
                                                <span class="bc-value">{{ $settings->website_url ?? 'boxmaker.co.in' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                </div>{{-- /.panel-content --}}

                                {{-- geometric decorations (clipped on the right edge) --}}
                                {{-- <div class="panel-decor">
                                    <svg viewBox="0 0 156 130" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="156" cy="64" r="74" fill="none" stroke="#3f4d5e" stroke-width="11"/>
                                        <circle cx="156" cy="64" r="54" fill="none" stroke="#ffbd59" stroke-width="8"/>
                                        <circle cx="156" cy="64" r="35" fill="none" stroke="#ffffff" stroke-opacity="0.88" stroke-width="6"/>
                                        <circle cx="156" cy="64" r="15" fill="#ffbd59"/>
                                        <rect x="14" y="18" width="3" height="42" rx="1.5" fill="#ffbd59" opacity="0.85"/>
                                        <rect x="22" y="18" width="3" height="42" rx="1.5" fill="#5b6a7a"/>
                                        <rect x="30" y="18" width="3" height="42" rx="1.5" fill="#ffbd59" opacity="0.85"/>
                                        <circle cx="16" cy="78" r="3" fill="#5b6a7a"/>
                                        <circle cx="25" cy="78" r="3" fill="#5b6a7a"/>
                                        <circle cx="34" cy="78" r="3" fill="#5b6a7a"/>
                                    </svg>
                                </div> --}}
                            </div>
                        </td>
                    </tr>
                </table>
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
                                    <div style="margin-bottom:5px;">{{ $quotation->client->factory_address ?? $quotation->client->address }}</div>
                                @endif
                                @if($quotation->client->gst_no ?? false)<div style="margin-bottom:5px;"><strong>GSTIN:</strong> {{ $quotation->client->gst_no }}</div>@endif
                                @if($quotation->client->phone ?? false)<div style="margin-bottom:5px;"><strong>Phone:</strong> {{ $quotation->client->phone }}</div>@endif
                                @if($quotation->client->email ?? false)<div style="margin-bottom:5px;"><strong>Email:</strong> {{ $quotation->client->email }}</div>@endif
                                @if($quotation->quotation_for ?? false)<div><strong>For:</strong> {{ $quotation->quotation_for }}</div>@endif
                            </div>
                        </td>
                        <td class="tt-right">
                            {{-- <div class="big-quotation">QUOTATION</div> --}}
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

    {{-- ============ CONTENT ROW ============ --}}
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
                        {{-- <tr>
                            <td>Subtotal</td>
                            <td>₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
                        </tr> --}}
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
                {{-- <div class="grand-total-wrap">
                    <table class="grand-total-bar">
                        <tr>
                            <td class="gt-label">Total (INR)</td>
                            <td class="gt-value">₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
                        </tr>
                    </table>
                </div> --}}
            </div>

        </div>
    </div>

    <div class="order-note">
        @if($quotation->notes)
            {{ $quotation->notes }}
        @endif
    </div>

    {{-- ============ CLOSING ROW - BOTTOM ALIGNED ============ --}}
    <div class="page-row">
        <div class="page-cell-bottom">
            <div class="closing-wrap">
                <table class="closing-table">
                    <tr>
                        {{-- <td class="cl-left">
                            <div class="thanks">Thank you for your business!</div>
                            @if($quotation->notes)
                            <div class="notes-title">Additional Notes</div>
                            <div class="notes-body">{{ $quotation->notes }}</div>
                            @endif
                        </td> --}}
                        <td class="cl-right">
                            @php
                                $settings = $settings ?? \App\Models\Setting::first();
                                $sigPath  = public_path('storage/' . $settings->signature);
                                $sigSrc   = '';
                                if ($settings->signature && file_exists($sigPath)) {
                                    $ext  = pathinfo($sigPath, PATHINFO_EXTENSION);
                                    $mime = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : 'image/png';
                                    $sigSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($sigPath));
                                }
                            @endphp

                            <div class="sig-block">
                                @if($sigSrc)
                                    <img src="{{ $sigSrc }}" class="sig-img" alt="Signature">
                                @else
                                    <div style="height:60px;"></div>
                                @endif
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
                <td class="footer-td"><strong>{{ $settings->website_url ?? 'https://boxmaker.co.in' }}</strong></td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>