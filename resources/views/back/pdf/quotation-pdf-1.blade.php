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
        font-family: Arial, sans-serif;
        color: #2d3743;
        font-size: 13px;
    }

    .page {
        width: 100%;
        min-height: 100%;
        position: relative;
        padding-bottom: 70px;
    }

    .top-accent {
        height: 5px;
        background: #ffbd59;
        width: 100%;
    }

    /* =============================================
       HEADER
    ============================================= */
    .header {
        display: table;
        width: 100%;
        padding: 28px 40px 22px 40px;
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

    .quotation-heading {
        font-size: 30px;
        font-weight: 900;
        color: #2d3743;
        letter-spacing: 1px;
        display: inline-block;
    }

    .meta-table { margin-top: 14px; border-collapse: collapse; }
    .meta-table td { padding: 4px 0; font-size: 13px; vertical-align: top; }
    .meta-key { color: #9aa5b0; width: 130px; font-weight: 500; }
    .meta-val  { color: #2d3743; font-weight: 700; }

    .logo-img { width: 110px; height: 110px; object-fit: contain; display: inline-block; }
    .logo-company { font-size: 11px; font-weight: 900; letter-spacing: 3px; color: #2d3743; text-transform: uppercase; margin-top: 6px; }
    .logo-tagline { font-size: 8px; letter-spacing: 2px; color: #ffbd59; text-transform: uppercase; font-weight: 700; margin-top: 2px; }
    .logo-recycle { font-size: 9px; color: #b0bac5; margin-top: 2px; }

    /* =============================================
       FROM / FOR BOXES
    ============================================= */
    .boxes-wrap {
        padding: 22px 40px;
        border-bottom: 1px solid #e8ecf0;
    }

    .boxes-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .box-cell + .box-cell {
        padding-left: 0;
    }

    .box-spacer {
        width: 14px;
    }

    /* Real <td> — wkhtmltopdf natively equalises height */
    .box-cell {
        width: 50%;
        vertical-align: top;
        background: #f7f9fb;
        border: 1px solid #e4e9ee;
        border-top: 3px solid #ffbd59;
        padding: 15px 18px;
    }

    .box-title { font-size: 9.5px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #ffbd59; margin-bottom: 9px; }
    .box-name  { font-size: 14px; font-weight: 800; color: #2d3743; margin-bottom: 5px; }
    .box-detail { font-size: 12.5px; color: #666; line-height: 1.65; }
    .box-detail strong { color: #2d3743; }

    /* =============================================
       ITEMS TABLE
    ============================================= */
    .table-wrap { padding: 26px 40px 0 40px; }

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

    .items-table { width: 100%; border-collapse: collapse; }

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

    .items-table tbody tr { border-bottom: 1px solid #edf0f4; }
    .items-table tbody tr:nth-child(even) { background: #f7f9fb; }
    .items-table tbody tr:nth-child(odd)  { background: #ffffff; }

    .items-table tbody td { padding: 13px 14px; font-size: 13px; color: #2d3743; vertical-align: middle; }
    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.right  { text-align: right; padding-right: 16px; font-weight: 700; }
    .items-table tbody td.num    { color: #aab0b8; font-weight: 700; font-size: 11px; width: 36px; }

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
       SUMMARY — right-aligned block, no side borders
    ============================================= */
    .summary-wrap {
        padding: 26px 40px 0 40px;
        text-align: right;
    }

    .summary-table {
        width: 320px;
        border-collapse: collapse;
        display: inline-table;
        text-align: left;
    }

    .summary-table tr td {
        padding: 10px 0;
        font-size: 13px;
        color: #555;
        border-bottom: 1px solid #f0f3f7;
        vertical-align: middle;
    }

    .summary-table tr td:last-child {
        text-align: right;
        font-weight: 700;
        color: #2d3743;
        padding-left: 40px;
    }

    .summary-total-row td {
        font-size: 15px !important;
        font-weight: 900 !important;
        color: #2d3743 !important;
        padding-top: 13px !important;
        padding-bottom: 13px !important;
        border-top: 2px solid #2d3743 !important;
        border-bottom: 2px solid #2d3743 !important;
    }

    /* =============================================
       ADDITIONAL NOTES — full width, centered below summary
    ============================================= */
    .notes-wrap {
        padding: 28px 40px 0 40px;
        text-align: center;
    }

    .notes-title {
        font-size: 13px;
        font-weight: 700;
        color: #ffbd59;
        margin-bottom: 8px;
    }

    .notes-body {
        font-size: 12.5px;
        color: #666;
        line-height: 1.75;
        font-style: italic;
    }

    /* =============================================
       SIGNATURE
    ============================================= */
    .sig-wrap {
        padding: 40px 40px 0 40px;
        text-align: right;
    }

    .sig-block {
        display: inline-block;
        width: 220px;
        text-align: right;
    }

    .sig-spacer { height: 44px; }

    .sig-line {
        border-top: 2px solid #ffbd59;
        padding-top: 8px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #2d3743;
    }

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

    .footer-table { width: 100%; border-collapse: collapse; }

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

    .footer-td:last-child { border-right: none; }
    .footer-td strong { color: #ffbd59; }
</style>

<body>
<div class="page">

    <div class="top-accent"></div>

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
        <div class="header-left">
            <div>
                <span class="quotation-heading">Quotation</span>
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

    {{-- FROM / FOR BOXES --}}
    <div class="boxes-wrap">
        <table class="boxes-table">
            <tr>
                <td class="box-cell">
                    <div class="box-title">Quotation From</div>
                    <div class="box-name">BOXMAKER</div>
                    <div class="box-detail">
                        {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai - 400092' }}<br>
                        @if($settings->gst_no ?? false)<strong>GSTIN:</strong> {{ $settings->gst_no }}<br>@endif
                        @if($settings->phone ?? false)<strong>Phone:</strong> {{ $settings->phone }}<br>@endif
                        @if($settings->email ?? false)<strong>Email:</strong> {{ $settings->email }}@endif
                    </div>
                </td>
                <td class="box-spacer"></td>
                <td class="box-cell">
                    <div class="box-title">Quotation For</div>
                    <div class="box-name">{{ $quotation->client->name ?? 'N/A' }}</div>
                    <div class="box-detail">
                        @if($quotation->client->factory_address ?? $quotation->client->address ?? false)
                            {{ $quotation->client->factory_address ?? $quotation->client->address }}<br>
                        @endif
                        @if($quotation->client->gst_no ?? false)<strong>GSTIN:</strong> {{ $quotation->client->gst_no }}<br>@endif
                        @if($quotation->client->phone ?? false)<strong>Phone:</strong> {{ $quotation->client->phone }}<br>@endif
                        @if($quotation->client->email ?? false)<strong>Email:</strong> {{ $quotation->client->email }}@endif
                        @if($quotation->quotation_for ?? false)<br><strong>For:</strong> {{ $quotation->quotation_for }}@endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="table-wrap">
        <div class="sec-title">Items &amp; Pricing</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:36px;">&nbsp;</th>
                    <th>Product, Material &amp; Size</th>
                    <th class="center" style="width:100px;">GSM</th>
                    <th class="right" style="width:150px;">Basic Price (₹)</th>
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
                    <td colspan="4" style="text-align:center;color:#aaa;padding:28px;font-style:italic;">No items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- SUMMARY — right aligned, no side borders --}}
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
            <tr class="summary-total-row">
                <td>Total (INR)</td>
                <td>₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ADDITIONAL NOTES — centered below summary --}}
    @if($quotation->notes)
    <div class="notes-wrap">
        <div class="notes-title">Additional Notes</div>
        <div class="notes-body">{{ $quotation->notes }}</div>
    </div>
    @endif

    {{-- SIGNATURE --}}
    <div class="sig-wrap">
        <div class="sig-block">
            <div class="sig-spacer"></div>
            <div class="sig-line">
                For BOXMAKER
                <div class="sig-sub">Authorised Signatory</div>
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