<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Quotation #{{ $quotation->quotation_number }}</title>
</head>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        width: 100%;
        background: #ffffff;
        font-family: 'Georgia', serif;
        color: #1a1a2e;
    }

    /* =============================================
       PAGE WRAPPER
    ============================================= */
    .page {
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 110px;
    }

    /* =============================================
       TOP ACCENT STRIPE
    ============================================= */
    .top-stripe {
        height: 6px;
        background: linear-gradient(90deg, #ffbd59 0%, #f5a623 50%, #ffbd59 100%);
        width: 100%;
    }

    /* =============================================
       HEADER
    ============================================= */
    .header {
        background: #2d3743;
        padding: 0;
        overflow: hidden;
    }

    .header-inner {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    /* Left: Logo area */
    .header-left {
        display: table-cell;
        width: 38%;
        vertical-align: middle;
        padding: 28px 30px 28px 35px;
        border-right: 1px solid rgba(255,189,89,0.25);
    }

    .logo-wrap {
        display: table;
        width: 100%;
    }

    .logo-img-cell {
        display: table-cell;
        vertical-align: middle;
        width: 90px;
    }

    .logo-img-cell img {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .logo-text-cell {
        display: table-cell;
        vertical-align: middle;
        padding-left: 12px;
    }

    .company-name {
        font-size: 26px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: 3px;
        font-family: Arial, sans-serif;
        text-transform: uppercase;
        line-height: 1;
    }

    .company-tagline {
        font-size: 11px;
        color: #ffbd59;
        letter-spacing: 2px;
        margin-top: 5px;
        font-family: Arial, sans-serif;
        text-transform: uppercase;
    }

    .company-sub {
        font-size: 10px;
        color: rgba(255,255,255,0.55);
        letter-spacing: 1.5px;
        margin-top: 4px;
        font-family: Arial, sans-serif;
        text-transform: uppercase;
    }

    /* Right: Quotation badge + info */
    .header-right {
        display: table-cell;
        width: 62%;
        vertical-align: top;
        padding: 0;
    }

    .quotation-title-bar {
        background: #ffbd59;
        padding: 16px 30px;
        display: table;
        width: 100%;
    }

    .quotation-title-bar-inner {
        display: table;
        width: 100%;
    }

    .quotation-word {
        display: table-cell;
        vertical-align: middle;
        font-size: 28px;
        font-weight: 900;
        color: #1a1a2e;
        letter-spacing: 5px;
        font-family: Arial, sans-serif;
        text-transform: uppercase;
    }

    .quotation-number {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        font-size: 13px;
        color: #1a1a2e;
        font-family: Arial, sans-serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .quotation-number span {
        font-size: 20px;
        display: block;
        font-weight: 900;
    }

    /* Info grid in header right */
    .header-info-grid {
        padding: 14px 30px 18px 30px;
    }

    .info-grid-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-grid-table tr td {
        padding: 5px 0;
        font-family: Arial, sans-serif;
        font-size: 13px;
        vertical-align: top;
    }

    .info-key {
        color: rgba(255,255,255,0.55);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding-bottom: 1px;
    }

    .info-val {
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding-right: 20px;
    }

    .info-val.large {
        font-size: 14px;
        color: #ffbd59;
    }

    /* Divider between info pairs */
    .info-divider {
        width: 1px;
        background: rgba(255,189,89,0.2);
        padding: 0 8px;
    }

    /* =============================================
       ADDRESS RIBBON
    ============================================= */
    .address-ribbon {
        background: #3d4f61;
        padding: 10px 35px;
        border-top: 1px solid rgba(255,189,89,0.15);
        border-bottom: 2px solid #ffbd59;
    }

    .address-ribbon p {
        font-size: 11.5px;
        color: rgba(255,255,255,0.82);
        font-family: Arial, sans-serif;
        letter-spacing: 0.5px;
        text-align: center;
    }

    /* =============================================
       BODY CONTENT
    ============================================= */
    .body-content {
        padding: 30px 35px 20px 35px;
    }

    /* Section label */
    .section-label {
        font-size: 9px;
        font-family: Arial, sans-serif;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #ffbd59;
        margin-bottom: 10px;
        padding-left: 10px;
        border-left: 3px solid #ffbd59;
    }

    /* =============================================
       ITEMS TABLE
    ============================================= */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .items-table thead tr th {
        background: #2d3743;
        color: #ffbd59;
        font-family: Arial, sans-serif;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 13px 16px;
    }

    .items-table thead tr th:first-child {
        width: 50px;
        text-align: center;
        border-radius: 0;
    }

    .items-table thead tr th.th-product {
        text-align: left;
    }

    .items-table thead tr th.th-center {
        text-align: center;
        width: 120px;
    }

    .items-table thead tr th.th-right {
        text-align: right;
        width: 140px;
        padding-right: 20px;
    }

    .items-table tbody tr {
        border-bottom: 1px solid #e8ecf0;
    }

    .items-table tbody tr:last-child {
        border-bottom: 2px solid #2d3743;
    }

    .items-table tbody tr:nth-child(even) {
        background: #f8f9fb;
    }

    .items-table tbody tr td {
        padding: 14px 16px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        color: #2d3743;
        vertical-align: middle;
    }

    .td-num {
        text-align: center;
        font-size: 11px;
        color: #888;
        font-weight: 700;
        width: 50px;
    }

    .td-product {
        font-weight: 600;
        color: #1a1a2e;
        line-height: 1.5;
    }

    .td-center {
        text-align: center;
        width: 120px;
        font-size: 13px;
        color: #555;
    }

    .td-price {
        text-align: right;
        width: 140px;
        font-weight: 700;
        font-size: 14px;
        color: #2d3743;
        padding-right: 20px;
    }

    .gsm-badge {
        display: inline-block;
        background: #eef2f7;
        color: #2d3743;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 3px;
        letter-spacing: 0.5px;
    }

    .empty-row td {
        text-align: center;
        color: #aaa;
        font-style: italic;
        padding: 30px;
    }

    /* =============================================
       BOTTOM SPLIT: INCLUSIONS + NOTES
    ============================================= */
    .bottom-grid {
        display: table;
        width: 100%;
        margin-top: 30px;
        border-collapse: collapse;
    }

    .bottom-left {
        display: table-cell;
        width: 48%;
        vertical-align: top;
        padding-right: 20px;
    }

    .bottom-right {
        display: table-cell;
        width: 52%;
        vertical-align: top;
        padding-left: 20px;
        border-left: 1px solid #e0e4e8;
    }

    /* Inclusions card */
    .inclusions-card {
        background: #f8f9fb;
        border: 1px solid #e0e4e8;
        border-top: 3px solid #ffbd59;
        padding: 18px 20px;
    }

    .inclusions-title {
        font-family: Arial, sans-serif;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 14px;
    }

    .inclusion-row {
        display: table;
        width: 100%;
        border-bottom: 1px solid #e8ecf0;
        padding: 8px 0;
    }

    .inclusion-row:last-child {
        border-bottom: none;
    }

    .inclusion-label {
        display: table-cell;
        font-family: Arial, sans-serif;
        font-size: 12.5px;
        color: #555;
        vertical-align: middle;
    }

    .inclusion-value {
        display: table-cell;
        text-align: right;
        font-family: Arial, sans-serif;
        font-size: 12px;
        font-weight: 700;
        vertical-align: middle;
    }

    .included-tag {
        background: #2d3743;
        color: #ffbd59;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 3px 9px;
        display: inline-block;
    }

    .not-included-tag {
        background: #f0f0f0;
        color: #bbb;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 3px 9px;
        display: inline-block;
    }

    /* Notes card */
    .notes-card {
        background: #fffbf3;
        border: 1px solid #ffe8a0;
        border-left: 4px solid #ffbd59;
        padding: 18px 20px;
        height: 100%;
    }

    .notes-title {
        font-family: Arial, sans-serif;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 12px;
    }

    .notes-text {
        font-family: Arial, sans-serif;
        font-size: 13px;
        color: #555;
        line-height: 1.7;
        font-style: italic;
    }

    /* =============================================
       VALIDITY / TERMS BAR
    ============================================= */
    .terms-bar {
        margin-top: 28px;
        background: #f0f3f7;
        border: 1px solid #dde2e8;
        padding: 14px 20px;
        display: table;
        width: 100%;
    }

    .terms-bar-left {
        display: table-cell;
        vertical-align: middle;
        font-family: Arial, sans-serif;
        font-size: 11.5px;
        color: #666;
    }

    .terms-bar-left strong {
        color: #2d3743;
    }

    .terms-bar-right {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #999;
        font-style: italic;
    }

    /* =============================================
       SIGNATURE SECTION
    ============================================= */
    .signature-section {
        display: table;
        width: 100%;
        margin-top: 40px;
        padding-bottom: 20px;
    }

    .sig-left {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-right: 30px;
    }

    .sig-right {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-left: 30px;
        text-align: right;
    }

    .sig-line {
        border-top: 1.5px solid #2d3743;
        padding-top: 7px;
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #666;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .sig-sub {
        font-size: 10px;
        color: #aaa;
        margin-top: 2px;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    /* =============================================
       PAGE FOOTER (fixed)
    ============================================= */
    .page-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #2d3743;
        padding: 0;
        border-top: 3px solid #ffbd59;
    }

    .footer-inner {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .footer-cell {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        padding: 14px 10px;
        border-right: 1px solid rgba(255,189,89,0.2);
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        font-weight: 500;
    }

    .footer-cell:last-child {
        border-right: none;
    }

    .footer-cell i-icon {
        display: inline-block;
        width: 18px;
        height: 18px;
        margin-right: 6px;
        vertical-align: middle;
        opacity: 0.7;
    }

    .footer-icon {
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
        font-size: 14px;
        opacity: 0.8;
    }

    .footer-highlight {
        color: #ffbd59;
        font-weight: 700;
    }

    /* =============================================
       DECORATIVE CORNER ELEMENT
    ============================================= */
    .corner-accent {
        position: absolute;
        top: 6px;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 40px 40px 0;
        border-color: transparent #ffbd59 transparent transparent;
    }

</style>

<body>

<div class="page">
    <div class="top-stripe"></div>

    {{-- ===================== HEADER ===================== --}}
    <div class="header">
        <div class="header-inner">
            {{-- LEFT: Logo + Company --}}
            <div class="header-left">
                @php
                    $settings = \App\Models\Setting::first();
                    $logoPath = public_path('images/black-logo.png');
                    $logoSrc  = '';
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoSrc  = 'data:image/png;base64,' . $logoData;
                    }
                @endphp
                <div class="logo-wrap">
                    @if($logoSrc)
                    <div class="logo-img-cell">
                        <img src="{{ $logoSrc }}" alt="Logo">
                    </div>
                    @endif
                    <div class="logo-text-cell">
                        <div class="company-name">BOXMAKER</div>
                        <div class="company-tagline">Packaging &amp; Printing</div>
                        <div class="company-sub">We Generally Recycle ♻</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Badge + Info --}}
            <div class="header-right">
                <div class="quotation-title-bar">
                    <div class="quotation-title-bar-inner">
                        <div class="quotation-word">Quotation</div>
                        <div class="quotation-number">
                            No.
                            <span>{{ $quotation->quotation_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="header-info-grid">
                    <table class="info-grid-table">
                        <tr>
                            <td style="width:50%; padding-right:15px;">
                                <div class="info-key">Company</div>
                                <div class="info-val large">{{ $quotation->client->name ?? 'N/A' }}</div>
                            </td>
                            <td style="width:25%;">
                                <div class="info-key">Date</div>
                                <div class="info-val">{{ $quotation->date->format('d / m / Y') }}</div>
                            </td>
                            <td style="width:25%;">
                                <div class="info-key">Valid Until</div>
                                <div class="info-val">
                                    {{ isset($quotation->valid_until) ? $quotation->valid_until->format('d / m / Y') : '—' }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:10px;">
                                <div class="info-key">Quotation For</div>
                                <div class="info-val">{{ $quotation->quotation_for ?? '—' }}</div>
                            </td>
                            <td style="padding-top:10px;" colspan="2">
                                <div class="info-key">Attention</div>
                                <div class="info-val">{{ $quotation->attention ?? '—' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== ADDRESS RIBBON ===================== --}}
    <div class="address-ribbon">
        <p>
            {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai – 400092' }}
        </p>
    </div>

    {{-- ===================== BODY ===================== --}}
    <div class="body-content">

        {{-- Items Table --}}
        <div class="section-label">Items &amp; Pricing</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align:center;">#</th>
                    <th class="th-product">Product, Material &amp; Size</th>
                    <th class="th-center">GSM</th>
                    <th class="th-right">Basic Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotation->items as $index => $item)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-product">{{ $item->particular }}</td>
                    <td class="td-center">
                        @if($item->gsm)
                            <span class="gsm-badge">{{ $item->gsm }}</span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td class="td-price">₹{{ number_format($item->base_price, 2) }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="4">No items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ===================== BOTTOM SPLIT ===================== --}}
        <div class="bottom-grid">

            {{-- LEFT: Inclusions --}}
            <div class="bottom-left">
                <div class="inclusions-card">
                    <div class="inclusions-title">What's Included</div>

                    @php
                        $inclusions = [
                            ['label' => 'Taxes',              'val' => $quotation->is_tax_included],
                            ['label' => 'Delivery Charges',   'val' => $quotation->is_delivery_charges_included],
                            ['label' => 'Printing',           'val' => $quotation->is_printing_included],
                            ['label' => 'Plate &amp; Punch',  'val' => $quotation->is_plate_and_punch],
                            ['label' => 'Lamination',         'val' => $quotation->is_lamination],
                        ];
                    @endphp

                    @foreach($inclusions as $inc)
                    <div class="inclusion-row">
                        <div class="inclusion-label">{!! $inc['label'] !!}</div>
                        <div class="inclusion-value">
                            @if($inc['val'])
                                <span class="included-tag">Included</span>
                            @else
                                <span class="not-included-tag">—</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Notes --}}
            <div class="bottom-right">
                @if($quotation->notes)
                <div class="notes-card">
                    <div class="notes-title">Notes &amp; Remarks</div>
                    <p class="notes-text">{{ $quotation->notes }}</p>
                </div>
                @else
                <div class="notes-card" style="background:#f8f9fb; border-color:#e0e4e8; border-left-color:#ccc;">
                    <div class="notes-title">Notes &amp; Remarks</div>
                    <p class="notes-text" style="color:#ccc;">No additional notes.</p>
                </div>
                @endif
            </div>

        </div>

        {{-- ===================== TERMS BAR ===================== --}}
        <div class="terms-bar">
            <div class="terms-bar-left">
                <strong>Payment Terms:</strong> &nbsp;All prices are subject to change without prior notice. &nbsp;|&nbsp;
                <strong>Delivery:</strong> &nbsp;As per mutual agreement.
            </div>
            <div class="terms-bar-right">This is a computer-generated quotation.</div>
        </div>

        {{-- ===================== SIGNATURES ===================== --}}
        <div class="signature-section">
            <div class="sig-left">
                <div style="height: 45px;"></div>
                <div class="sig-line">
                    Client Acknowledgement
                    <div class="sig-sub">Signature &amp; Company Stamp</div>
                </div>
            </div>
            <div class="sig-right">
                <div style="height: 45px;"></div>
                <div class="sig-line" style="border-top-color:#ffbd59;">
                    For BOXMAKER
                    <div class="sig-sub">Authorised Signatory</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== PAGE FOOTER ===================== --}}
    <div class="page-footer">
        <div class="footer-inner">
            <div class="footer-cell">
                📞 &nbsp;<span class="footer-highlight">{{ $settings->phone ?? '+91 9820006001' }}</span>
            </div>
            <div class="footer-cell">
                ✉ &nbsp;{{ $settings->email ?? 'boxmaker@myyahoo.com' }}
            </div>
            <div class="footer-cell">
                🌐 &nbsp;<span class="footer-highlight">{{ $settings->website_url ?? 'www.myboxmaker.com' }}</span>
            </div>
        </div>
    </div>

</div>

</body>
</html>