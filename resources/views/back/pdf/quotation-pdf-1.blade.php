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
        font-family: Arial, sans-serif;
        color: #2d3743;
    }

    .page {
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 90px;
    }

    /* =============================================
       TOP BAR — gold + dark split
    ============================================= */
    .top-bar {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .top-bar-left {
        display: table-cell;
        vertical-align: middle;
        padding: 16px 35px;
        width: 55%;
        background: #ffbd59;
    }

    .top-bar-title {
        font-size: 34px;
        font-weight: 900;
        letter-spacing: 8px;
        color: #2d3743;
        text-transform: uppercase;
    }

    .top-bar-right {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        padding: 16px 35px;
        width: 45%;
        background: #2d3743;
    }

    .top-bar-num-label {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 3px;
        color: rgba(255,189,89,0.6);
        text-transform: uppercase;
    }

    .top-bar-num {
        font-size: 30px;
        font-weight: 900;
        color: #ffbd59;
        letter-spacing: 2px;
    }

    /* =============================================
       HEADER — white background, logo left
    ============================================= */
    .header {
        display: table;
        width: 100%;
        padding: 28px 35px;
        background: #ffffff;
        border-bottom: 2px solid #f0f3f7;
    }

    .header-logo-cell {
        display: table-cell;
        vertical-align: middle;
        width: 32%;
        padding-right: 30px;
    }

    .header-logo-cell img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        display: block;
        margin-bottom: 10px;
    }

    .company-name {
        font-size: 20px;
        font-weight: 900;
        color: #2d3743;
        letter-spacing: 4px;
        text-transform: uppercase;
    }

    .company-tagline {
        font-size: 8.5px;
        letter-spacing: 2.5px;
        color: #ffbd59;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 4px;
    }

    .company-recycle {
        font-size: 9px;
        color: #b0bac5;
        margin-top: 4px;
        letter-spacing: 0.5px;
    }

    /* Vertical divider */
    .header-divider-cell {
        display: table-cell;
        width: 1px;
        background: #e8ecf0;
        padding: 0;
    }

    /* Info grid */
    .header-info-cell {
        display: table-cell;
        vertical-align: top;
        width: 68%;
        padding-left: 30px;
    }

    .info-block-label {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #b0bac5;
        margin-bottom: 4px;
    }

    .info-block-value {
        font-size: 13.5px;
        font-weight: 700;
        color: #2d3743;
        line-height: 1.45;
    }

    .info-block-value.gold {
        color: #c98a00;
        font-size: 15px;
    }

    /* =============================================
       ADDRESS STRIP
    ============================================= */
    .address-strip {
        background: #f4f6f9;
        border-bottom: 3px solid #ffbd59;
        padding: 10px 35px;
        text-align: center;
    }

    .address-strip p {
        font-size: 11px;
        color: #777;
        letter-spacing: 0.4px;
    }

    /* =============================================
       BODY
    ============================================= */
    .body {
        padding: 28px 35px 20px;
    }

    /* Section heading */
    .sec-title {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 12px;
        padding-left: 12px;
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
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 13px 16px;
    }

    .items-table thead tr th.th-num {
        width: 46px;
        text-align: center;
    }

    .items-table thead tr th.th-prod {
        text-align: left;
    }

    .items-table thead tr th.th-gsm {
        text-align: center;
        width: 110px;
    }

    .items-table thead tr th.th-price {
        text-align: right;
        width: 155px;
        padding-right: 20px;
    }

    .items-table tbody tr {
        border-bottom: 1px solid #edf0f4;
    }

    .items-table tbody tr:last-child {
        border-bottom: 2px solid #2d3743;
    }

    .items-table tbody tr:nth-child(even) {
        background: #fafbfc;
    }

    .items-table tbody tr:nth-child(odd) {
        background: #ffffff;
    }

    .items-table tbody td {
        padding: 15px 16px;
        font-size: 13px;
        color: #2d3743;
        vertical-align: middle;
    }

    .td-num {
        text-align: center;
        font-size: 10.5px;
        font-weight: 700;
        color: #c0c8d0;
    }

    .td-product {
        font-weight: 600;
        color: #1a252f;
        line-height: 1.55;
    }

    .td-gsm {
        text-align: center;
    }

    .gsm-pill {
        display: inline-block;
        background: #eef2f7;
        border: 1px solid #dde3ea;
        color: #2d3743;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .td-price {
        text-align: right;
        font-weight: 800;
        font-size: 15px;
        color: #2d3743;
        padding-right: 20px;
    }

    /* =============================================
       BOTTOM SPLIT
    ============================================= */
    .bottom-wrap {
        display: table;
        width: 100%;
        margin-top: 28px;
        border-collapse: collapse;
    }

    .bottom-left {
        display: table-cell;
        width: 44%;
        vertical-align: top;
        padding-right: 18px;
    }

    .bottom-right {
        display: table-cell;
        width: 56%;
        vertical-align: top;
        padding-left: 18px;
        border-left: 1px solid #e8ecf0;
    }

    /* Inclusions */
    .inc-card {
        border: 1px solid #e0e5ea;
        border-top: 4px solid #ffbd59;
    }

    .inc-card-head {
        background: #f7f9fb;
        border-bottom: 1px solid #e8ecf0;
        padding: 10px 16px;
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
    }

    .inc-row {
        display: table;
        width: 100%;
        padding: 10px 16px;
        border-bottom: 1px solid #f0f3f7;
    }

    .inc-row:last-child {
        border-bottom: none;
    }

    .inc-label {
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
        font-size: 8px;
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

    /* Notes */
    .notes-card {
        border: 1px solid #f0e6c0;
        border-left: 4px solid #ffbd59;
        background: #fffdf5;
        padding: 16px 18px;
        min-height: 100%;
    }

    .notes-head {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 10px;
    }

    .notes-body {
        font-size: 13px;
        color: #666;
        line-height: 1.75;
        font-style: italic;
    }

    /* =============================================
       TERMS BAR
    ============================================= */
    .terms-bar {
        margin-top: 24px;
        background: #f7f9fb;
        border: 1px solid #e0e5ea;
        border-left: 4px solid #2d3743;
        padding: 12px 18px;
        display: table;
        width: 100%;
    }

    .terms-l {
        display: table-cell;
        vertical-align: middle;
        font-size: 11.5px;
        color: #555;
        width: 70%;
    }

    .terms-l strong {
        color: #2d3743;
    }

    .terms-r {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        font-size: 10px;
        color: #c0c8d0;
        font-style: italic;
        width: 30%;
    }

    /* =============================================
       SIGNATURES
    ============================================= */
    .sig-wrap {
        display: table;
        width: 100%;
        margin-top: 48px;
    }

    .sig-left {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-right: 50px;
    }

    .sig-right {
        display: table-cell;
        width: 50%;
        vertical-align: bottom;
        padding-left: 50px;
        text-align: right;
    }

    .sig-line {
        border-top: 2px solid #2d3743;
        padding-top: 8px;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #2d3743;
    }

    .sig-line.gold-line {
        border-top-color: #ffbd59;
    }

    .sig-sub {
        font-size: 9px;
        color: #c0c8d0;
        margin-top: 3px;
        font-weight: 400;
        letter-spacing: 0.5px;
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
        padding: 13px 10px;
        font-size: 12px;
        color: rgba(255,255,255,0.7);
        border-right: 1px solid rgba(255,189,89,0.15);
        width: 33.33%;
    }

    .footer-td:last-child {
        border-right: none;
    }

    .footer-td strong {
        color: #ffbd59;
    }
</style>

<body>
<div class="page">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-left">
            <div class="top-bar-title">Quotation</div>
        </div>
        <div class="top-bar-right">
            <div class="top-bar-num-label">Quotation No.</div>
            <div class="top-bar-num">{{ $quotation->quotation_number }}</div>
        </div>
    </div>

    {{-- HEADER --}}
    @php
        $settings = \App\Models\Setting::first();
        $logoPath = public_path('images/black-logo.png');
        $logoSrc  = '';
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header">
        <div class="header-logo-cell">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Boxmaker Logo">
            @endif
            <div class="company-name">BOXMAKER</div>
            <div class="company-tagline">Packaging &amp; Printing</div>
            <div class="company-recycle">♻ We Generally Recycle</div>
        </div>

        <div class="header-divider-cell">&nbsp;</div>

        <div class="header-info-cell">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:46%; padding:0 20px 16px 0; vertical-align:top; border-bottom:1px solid #f0f3f7;">
                        <div class="info-block-label">Company</div>
                        <div class="info-block-value gold">{{ $quotation->client->name ?? 'N/A' }}</div>
                    </td>
                    <td style="width:27%; padding:0 20px 16px 0; vertical-align:top; border-bottom:1px solid #f0f3f7;">
                        <div class="info-block-label">Date</div>
                        <div class="info-block-value">{{ $quotation->date->format('d / m / Y') }}</div>
                    </td>
                    <td style="width:27%; padding:0 0 16px 0; vertical-align:top; border-bottom:1px solid #f0f3f7;">
                        <div class="info-block-label">Valid Until</div>
                        <div class="info-block-value">
                            {{ isset($quotation->valid_until) ? $quotation->valid_until->format('d / m / Y') : '—' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 20px 0 0; vertical-align:top;">
                        <div class="info-block-label">Quotation For</div>
                        <div class="info-block-value">{{ $quotation->quotation_for ?? '—' }}</div>
                    </td>
                    <td colspan="2" style="padding:16px 0 0 0; vertical-align:top;">
                        <div class="info-block-label">Attention</div>
                        <div class="info-block-value">{{ $quotation->attention ?? '—' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ADDRESS STRIP --}}
    <div class="address-strip">
        <p>{{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai – 400092' }}</p>
    </div>

    {{-- BODY --}}
    <div class="body">

        <div class="sec-title">Items &amp; Pricing</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="th-num">#</th>
                    <th class="th-prod">Product, Material &amp; Size</th>
                    <th class="th-gsm">GSM</th>
                    <th class="th-price">Basic Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotation->items as $index => $item)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-product">{{ $item->particular }}</td>
                    <td class="td-gsm">
                        @if($item->gsm)
                            <span class="gsm-pill">{{ $item->gsm }}</span>
                        @else
                            <span style="color:#d0d5db; font-size:14px;">—</span>
                        @endif
                    </td>
                    <td class="td-price">₹{{ number_format($item->base_price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#aaa; padding:30px; font-style:italic;">
                        No items found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- BOTTOM SPLIT --}}
        <div class="bottom-wrap">
            <div class="bottom-left">
                <div class="inc-card">
                    <div class="inc-card-head">What's Included</div>
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
                        <div class="inc-label">{{ $label }}</div>
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

            <div class="bottom-right">
                <div class="notes-card">
                    <div class="notes-head">Notes &amp; Remarks</div>
                    @if($quotation->notes)
                        <p class="notes-body">{{ $quotation->notes }}</p>
                    @else
                        <p class="notes-body" style="color:#ccc;">No additional notes for this quotation.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- TERMS --}}
        <div class="terms-bar">
            <div class="terms-l">
                <strong>Payment Terms:</strong> &nbsp;Prices subject to change without prior notice. &nbsp;|&nbsp;
                <strong>Delivery:</strong> &nbsp;As per mutual agreement.
            </div>
            <div class="terms-r">Computer-generated document.</div>
        </div>

        {{-- SIGNATURES --}}
        <div class="sig-wrap">
            <div class="sig-left">
                <div style="height:50px;"></div>
                <div class="sig-line">
                    Client Acknowledgement
                    <div class="sig-sub">Signature &amp; Company Stamp</div>
                </div>
            </div>
            <div class="sig-right">
                <div style="height:50px;"></div>
                <div class="sig-line gold-line">
                    For BOXMAKER
                    <div class="sig-sub">Authorised Signatory</div>
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
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