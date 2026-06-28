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

    .page {
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 120px; /* reduced from 135px */
        display: flex;
        flex-direction: column;
    }

    /* =============================================
       DARK WAVY HEADER
    ============================================= */
    .header {
        position: relative;
        background: #2d3743;
        padding: 20px 40px 70px 40px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { vertical-align: top; }

    .hdr-left  { width: 58%; }
    .hdr-right { width: 42%; text-align: right; }

    .logo-badge {
        display: inline-block;
        background: #ffffff;
        border-radius: 10px;
        padding: 5px;
        vertical-align: middle;
        line-height: 0;
    }
    .logo-img { width: 55px; height: 55px; object-fit: contain; display: block; }

    .brand-block { display: inline-block; vertical-align: middle; padding-left: 10px; }
    .brand-name {
        font-size: 18px;
        font-weight: 900;
        letter-spacing: 2px;
        color: #ffffff;
        text-transform: uppercase;
        line-height: 1.1;
    }
    .brand-tagline {
        font-size: 7px;
        letter-spacing: 2px;
        color: #ffbd59;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 2px;
    }
    .brand-recycle {
        font-size: 7px;
        color: rgba(255,255,255,0.45);
        margin-top: 2px;
        letter-spacing: 0.5px;
    }

    .brand-meta {
        margin-top: 8px;
        font-size: 9px;
        color: rgba(255,255,255,0.62);
        line-height: 1.4;
        max-width: 320px;
    }
    .brand-meta strong { color: #ffbd59; font-weight: 700; }

    .contact-table { border-collapse: collapse; display: inline-table; text-align: left; }
    .contact-table td { padding: 0 0 0 15px; vertical-align: top; }
    .contact-label {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 2px;
    }
    .contact-val {
        font-size: 9px;
        color: rgba(255,255,255,0.82);
        line-height: 1.4;
        white-space: nowrap;
    }

    .header-wave {
        position: absolute; left: 0; bottom: -1px;
        width: 100%; height: 65px; display: block;
    }

    /* =============================================
       TITLE + META + RECIPIENT
    ============================================= */
    .title-wrap { padding: 18px 40px 0 40px; flex-shrink: 0; }
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
       ITEMS TABLE - FLEXIBLE CONTENT
    ============================================= */
    .table-wrap { 
        padding: 15px 40px 0 40px;
        flex-shrink: 0;
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
       SUMMARY - FLEXIBLE CONTENT
    ============================================= */
    .summary-wrap { 
        padding: 10px 40px 0 40px; /* reduced from 12px */
        text-align: right;
        flex-shrink: 0;
    }
    .summary-table { 
        width: 300px; 
        border-collapse: collapse; 
        display: inline-table; 
        text-align: left;
    }
    .summary-table td {
        padding: 4px 0; /* reduced from 5px */
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
        padding: 6px 40px 0 40px; /* reduced from 8px */
        text-align: right;
        flex-shrink: 0;
    }
    .grand-total-bar {
        display: inline-table;
        width: 300px;
        background: #ffbd59;
        border-collapse: collapse;
        border-radius: 4px;
    }
    .grand-total-bar td {
        padding: 8px 14px; /* reduced from 10px */
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
       CLOSING SECTION - FIXED AT BOTTOM
    ============================================= */
    .closing-wrap {
        position: absolute;
        bottom: 60px; /* reduced from 70px */
        left: 0;
        right: 0;
        padding: 10px 40px 0 40px; /* reduced padding */
        background: #ffffff;
    }
    .closing-table { 
        width: 100%; 
        border-collapse: collapse;
    }
    .closing-table td { vertical-align: bottom; }
    .cl-left  { width: 58%; padding-right: 25px; }
    .cl-right { width: 42%; text-align: right; }

    .thanks {
        font-size: 16px; /* increased from 15px */
        font-weight: 900;
        color: #2d3743;
        margin-bottom: 4px; /* reduced from 6px */
    }

    .notes-title {
        font-size: 9px; /* increased from 8px */
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 2px; /* reduced from 3px */
    }
    .notes-body {
        font-size: 11px; /* increased from 10px */
        color: #6b7682;
        line-height: 1.4;
    }

    .sig-block { 
        display: inline-block; 
        width: 200px; 
        text-align: center;
    }
    .sig-line {
        border-top: 2px solid #ffbd59;
        padding-top: 6px;
        font-size: 10px; /* increased from 9px */
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #2d3743;
    }
    .sig-sub {
        font-size: 9px; /* increased from 8px */
        color: #aeb6bf;
        margin-top: 1px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* =============================================
       FIXED FOOTER BAR - REDUCED PADDING, BIGGER TEXT
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
        height: 60px; /* reduced from 70px */
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
        padding: 6px 8px; /* reduced from 10px 8px */
        font-size: 12px; /* increased from 10px */
        color: rgba(255,255,255,0.72);
        border-right: 1px solid rgba(255,189,89,0.15);
        width: 33.33%;
        word-wrap: break-word;
    }
    .footer-td:last-child { border-right: none; }
    .footer-td strong { color: #ffbd59; font-weight: 700; }

    /* =============================================
       SPACER TO PUSH CONTENT UP
    ============================================= */
    .content-spacer {
        flex: 1;
        min-height: 10px; /* reduced from 20px */
    }

    @media print {
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .closing-wrap {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
        }
    }

    @media screen {
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .closing-wrap {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
        }
    }
</style>

<body>
<div class="page">

    {{-- ============ HEADER ============ --}}
    @php
        $settings = \App\Models\Setting::first();
        $logoPath = public_path('images/black-logo.png');
        $logoSrc  = '';
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="hdr-left">
                    @if($logoSrc)
                        <span class="logo-badge"><img src="{{ $logoSrc }}" alt="Boxmaker Logo" class="logo-img"></span>
                    @endif
                    <span class="brand-block">
                        <div class="brand-name">Boxmaker</div>
                        <div class="brand-tagline">Packaging &amp; Printing</div>
                        <div class="brand-recycle">&#9851; We Generally Recycle</div>
                    </span>
                    <div class="brand-meta">
                        {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai - 400092' }}<br>
                        <strong>GSTIN:</strong> {{ $settings->gst_no ?? '27ABDFB7083N1ZY' }}
                    </div>
                </td>
                <td class="hdr-right">
                    <table class="contact-table">
                        <tr>
                            <td>
                                <div class="contact-label">Phone</div>
                                <div class="contact-val">{{ $settings->phone ?? '+91 9820006001' }}</div>
                            </td>
                            <td>
                                <div class="contact-label">Email</div>
                                <div class="contact-val">{{ $settings->email ?? 'boxmaker@myyahoo.com' }}</div>
                            </td>
                            <td>
                                <div class="contact-label">Web</div>
                                <div class="contact-val">{{ $settings->website_url ?? 'www.myboxmaker.com' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- WAVE DIVIDER --}}
        <svg class="header-wave" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#9aa5b0" opacity="0.30" d="M-20,52 C210,96 400,22 620,54 C820,83 1010,30 1220,56 L1220,130 L-20,130 Z"/>
            <path fill="#ffffff" d="M-20,80 C230,116 410,54 640,82 C840,106 1020,60 1220,82 L1220,130 L-20,130 Z"/>
        </svg>
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

    {{-- ============ ITEMS TABLE ============ --}}
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

    {{-- ============ SPACER TO PUSH CONTENT ============ --}}
    <div class="content-spacer"></div>

    {{-- ============ CLOSING SECTION - FIXED AT BOTTOM ============ --}}
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