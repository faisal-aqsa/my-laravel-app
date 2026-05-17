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
    }

    .page {
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 80px;
    }

    /* =============================================
       HEADER — full-width dark background
    ============================================= */
    .header {
        background: #2d3743;
        padding: 30px 40px;
    }

    /* Logo centered at top */
    .header-logo-wrap {
        text-align: center;
        margin-bottom: 22px;
        padding-bottom: 22px;
        border-bottom: 1px solid rgba(255,189,89,0.25);
    }

    .header-logo-wrap img {
        height: 90px;
        width: auto;
        object-fit: contain;
        display: inline-block;
        /* invert black logo to white for dark background */
        filter: brightness(0) invert(1);
    }

    .header-logo-wrap .company-name {
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 6px;
        color: #ffffff;
        text-transform: uppercase;
        margin-top: 8px;
    }

    .header-logo-wrap .company-sub {
        font-size: 8px;
        letter-spacing: 3px;
        color: #ffbd59;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 3px;
    }

    /* Three-column info row */
    .header-meta {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .header-meta-col {
        display: table-cell;
        vertical-align: top;
        width: 33.33%;
        padding: 0 15px;
        border-right: 1px solid rgba(255,189,89,0.15);
    }

    .header-meta-col:first-child { padding-left: 0; }
    .header-meta-col:last-child  { padding-right: 0; border-right: none; text-align: right; }
    .header-meta-col.center      { text-align: center; }

    .meta-label {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: rgba(255,189,89,0.65);
        margin-bottom: 5px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.45;
    }

    .meta-value.gold {
        color: #ffbd59;
        font-size: 14px;
    }

    .meta-value.big {
        font-size: 28px;
        font-weight: 900;
        color: #ffbd59;
        letter-spacing: 2px;
        line-height: 1;
    }

    .meta-value.date-val {
        font-size: 13px;
        color: #ffffff;
    }

    /* =============================================
       GOLD RIBBON
    ============================================= */
    .gold-ribbon {
        background: #ffbd59;
        padding: 9px 40px;
        text-align: center;
    }

    .gold-ribbon p {
        font-size: 11px;
        font-weight: 600;
        color: #2d3743;
        letter-spacing: 0.4px;
    }

    /* =============================================
       BODY
    ============================================= */
    .body {
        padding: 30px 40px 20px;
    }

    /* Section title */
    .sec-title {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 14px;
        padding-left: 10px;
        border-left: 3px solid #ffbd59;
    }

    /* =============================================
       ITEMS TABLE
    ============================================= */
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
        padding: 12px 16px;
    }

    .items-table thead tr th.th-num   { text-align: center; width: 44px; }
    .items-table thead tr th.th-prod  { text-align: left; }
    .items-table thead tr th.th-gsm   { text-align: center; width: 105px; }
    .items-table thead tr th.th-price { text-align: right; width: 150px; padding-right: 18px; }

    .items-table tbody tr {
        border-bottom: 1px solid #edf0f4;
    }

    .items-table tbody tr:last-child {
        border-bottom: 2px solid #2d3743;
    }

    .items-table tbody tr:nth-child(even) { background: #f9fafb; }
    .items-table tbody tr:nth-child(odd)  { background: #ffffff; }

    .items-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: #2d3743;
        vertical-align: middle;
    }

    .td-num   { text-align: center; font-size: 10px; font-weight: 700; color: #c0c8d0; }
    .td-prod  { font-weight: 600; color: #1a252f; line-height: 1.5; }
    .td-gsm   { text-align: center; }
    .td-price { text-align: right; font-weight: 800; font-size: 15px; padding-right: 18px; }

    .gsm-pill {
        display: inline-block;
        background: #edf1f6;
        border: 1px solid #dde3ea;
        color: #2d3743;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 11px;
        border-radius: 20px;
    }

    /* =============================================
       BOTTOM SPLIT
    ============================================= */
    .bottom-wrap {
        display: table;
        width: 100%;
        margin-top: 26px;
        border-collapse: collapse;
    }

    .bot-left {
        display: table-cell;
        width: 42%;
        vertical-align: top;
        padding-right: 20px;
    }

    .bot-right {
        display: table-cell;
        width: 58%;
        vertical-align: top;
        padding-left: 20px;
        border-left: 1px solid #e8ecf0;
    }

    /* Inclusions */
    .inc-card {
        border: 1px solid #e0e5ea;
        border-top: 4px solid #ffbd59;
    }

    .inc-head {
        background: #f7f9fb;
        border-bottom: 1px solid #e8ecf0;
        padding: 9px 15px;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
    }

    .inc-row {
        display: table;
        width: 100%;
        padding: 9px 15px;
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

    .tag-no { font-size: 14px; color: #d5dbe0; font-weight: 700; }

    /* Notes */
    .notes-card {
        border: 1px solid #f0e6c0;
        border-left: 4px solid #ffbd59;
        background: #fffdf6;
        padding: 15px 18px;
    }

    .notes-head {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2d3743;
        margin-bottom: 9px;
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
        margin-top: 22px;
        background: #f7f9fb;
        border: 1px solid #e0e5ea;
        border-left: 4px solid #2d3743;
        padding: 11px 16px;
        display: table;
        width: 100%;
    }

    .terms-l {
        display: table-cell;
        vertical-align: middle;
        font-size: 11.5px;
        color: #555;
        width: 72%;
    }

    .terms-l strong { color: #2d3743; }

    .terms-r {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        font-size: 10px;
        color: #c0c8d0;
        font-style: italic;
        width: 28%;
    }

    /* =============================================
       SIGNATURES
    ============================================= */
    .sig-wrap {
        display: table;
        width: 100%;
        margin-top: 50px;
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

    .sig-line {
        border-top: 2px solid #2d3743;
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
        margin-top: 3px;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    /* =============================================
       PAGE FOOTER
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

    {{-- ===================== HEADER (dark) ===================== --}}
    @php
        $settings = \App\Models\Setting::first();
        $logoPath = public_path('images/black-logo.png');
        $logoSrc  = '';
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header">

        {{-- Logo + Company name, centered --}}
        <div class="header-logo-wrap">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Boxmaker Logo"><br>
            @endif
            <div class="company-name">BOXMAKER</div>
            <div class="company-sub">Packaging &amp; Printing &nbsp;·&nbsp; We Generally Recycle ♻</div>
        </div>

        {{-- Three-column meta: Client | Quotation | Date --}}
        <div class="header-meta">

            {{-- Left: Client details --}}
            <div class="header-meta-col">
                <div class="meta-label">Company</div>
                <div class="meta-value gold">{{ $quotation->client->name ?? 'N/A' }}</div>

                @if($quotation->attention)
                    <div class="meta-label" style="margin-top:12px;">Attention</div>
                    <div class="meta-value">{{ $quotation->attention }}</div>
                @endif

                @if($quotation->quotation_for)
                    <div class="meta-label" style="margin-top:12px;">Quotation For</div>
                    <div class="meta-value">{{ $quotation->quotation_for }}</div>
                @endif
            </div>

            {{-- Center: Quotation number --}}
            <div class="header-meta-col center">
                <div class="meta-label">Quotation No.</div>
                <div class="meta-value big">{{ $quotation->quotation_number }}</div>
            </div>

            {{-- Right: Dates --}}
            <div class="header-meta-col">
                <div class="meta-label">Date</div>
                <div class="meta-value date-val">{{ $quotation->date->format('d M Y') }}</div>

                <div class="meta-label" style="margin-top:12px;">Valid Until</div>
                <div class="meta-value date-val">
                    {{ isset($quotation->valid_until) ? $quotation->valid_until->format('d M Y') : 'On Request' }}
                </div>
            </div>

        </div>
    </div>

    {{-- ===================== GOLD RIBBON ===================== --}}
    <div class="gold-ribbon">
        <p>{{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai – 400092' }}</p>
    </div>

    {{-- ===================== BODY ===================== --}}
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
                    <td class="td-prod">{{ $item->particular }}</td>
                    <td class="td-gsm">
                        @if($item->gsm)
                            <span class="gsm-pill">{{ $item->gsm }}</span>
                        @else
                            <span style="color:#d0d5db;">—</span>
                        @endif
                    </td>
                    <td class="td-price">₹{{ number_format($item->base_price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#aaa; padding:28px; font-style:italic;">
                        No items found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Bottom split --}}
        <div class="bottom-wrap">

            <div class="bot-left">
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

            <div class="bot-right">
                <div class="notes-card">
                    <div class="notes-head">Notes &amp; Remarks</div>
                    @if($quotation->notes)
                        <p class="notes-body">{{ $quotation->notes }}</p>
                    @else
                        <p class="notes-body" style="color:#ccc;">No additional notes.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Terms --}}
        <div class="terms-bar">
            <div class="terms-l">
                <strong>Payment Terms:</strong> &nbsp;Prices subject to change without prior notice. &nbsp;|&nbsp;
                <strong>Delivery:</strong> &nbsp;As per mutual agreement.
            </div>
            <div class="terms-r">Computer-generated document.</div>
        </div>

        {{-- Signatures --}}
        <div class="sig-wrap">
            <div class="sig-l">
                <div style="height:48px;"></div>
                <div class="sig-line">
                    Client Acknowledgement
                    <div class="sig-sub">Signature &amp; Company Stamp</div>
                </div>
            </div>
            <div class="sig-r">
                <div style="height:48px;"></div>
                <div class="sig-line gold-top">
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