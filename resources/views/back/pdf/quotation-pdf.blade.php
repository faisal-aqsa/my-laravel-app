<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Quotation #{{ $quotation->id }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&family=Lobster&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Pacifico&family=Playwrite+NZ+Basic:wght@100..400&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap" rel="stylesheet">
</head>

<style>
    * {
        box-sizing: border-box;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    html, body {
        height: 100%;
        background: #fff;
        padding: 0;
    }

    .container {
        width: 100%;
        min-height: 100%;
        position: relative;
        padding-bottom: 100px; /* reserve space for fixed footer */
    }

    /* ===== HEADER ===== */
    .header {
        /* background: #2d3743; */
        /* padding: 40px 0; */
        overflow: hidden;
    }

    .header-logo {
        float: left;
        width: 30%;
        text-align: center;
        padding: 10px;
    }

    .header-logo img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        filter: invert(1);
    }

    /* ===== HEADER TABLE ===== */
    .header-table {
        width: 100%;
        background: #ffffff;
        border-collapse: collapse;
    }

    .logo-cell {
        width: 35%;
        text-align: center;
        padding: 30px 20px;
        vertical-align: middle;
        /* border-right: 1px solid #4a5568; */
    }

    .logo-cell img {
        width: 110px;
        height: 110px;
        object-fit: contain;
        filter: invert(1);
        display: block;
        margin: 0 auto;
    }

    .tag-line {
        margin-top:8px; 
        font-weight:500; 
        font-family: "Lobster", cursive;
        display:flex; 
        align-items:center; 
        gap:6px;
        font-size: 16px; 
    }

    .info-cell {
        width: 65%;
        vertical-align: top;
        padding: 0;
    }

    /* QUOTATION badge - full width across info cell, fixed at top */
    .quotation-badge {
        background: #ffbd59;
        text-align: center;
        padding: 18px 0;
        width: 100%;
    }

    .quotation-badge span {
        font-size: 26px;
        font-weight: 700;
        color: #1f2933;
    }

    /* Info rows below badge */
    .info-inner-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    .info-inner-table td {
        padding: 8px;
        font-size: 15px;
        vertical-align: top;
        padding: 10px 10px;
    }

    .label-cell {
        width: 40%;
        color: #000000;
        font-weight: 800;
        white-space: nowrap;
        padding-left: 20px;
    }

    .value-cell {
        width: 60%;
        color: #000000;
        text-align: right;
        padding-right: 20px;
    }

    .info-table {
        width: auto;
        margin: 0 auto;
        border-collapse: collapse;
    }

    .info-table td {
        font-size: 15px;
        padding: 7px 12px;
        vertical-align: top;
    }

    .color-yellow {
        color: #ffbd59;
        font-weight: 600;
        white-space: nowrap;
    }

    .color-white {
        color: #ffffff;
    }

    /* ===== QUOTATION BADGE (top right in header) ===== */
    .header-badge {
        float: left;
        width: 30%;
        text-align: center;
        padding: 10px;
    }

    .quotation-badge {
        display: inline-block;
        background: #ffbd59;
        padding: 14px 28px;
    }

    .quotation-badge span {
        font-size: 30px;
        font-weight: 700;
        color: #000000;
    }

    /* ===== HEADER BOTTOM (address bar) ===== */
    .header-bottom {
        background: #2d3743;
        overflow: hidden;
        padding: 20px 30px;
        text-align: center;
    }

    .address-text {
        font-size: 13px;
        color: #ffffff;
        line-height: 1.7;
        font-weight: bold;
    }

    /* ===== QUOTATION DATA ===== */
    .section-wrap {
        overflow: hidden;
        padding: 20px;
    }

    .colm-table {
        width: 100%;
        border-collapse: collapse;
    }

    .colm-header th {
        background: #ffbd59;
        font-size: 14px;
        font-weight: 700;
        color: #000;
        padding: 14px 10px;
        text-align: center;
    }

    .col-gsm,
    .col-price {
        text-align: center;
    }

    .col-product { width: 60%; }
    .col-gsm     { width: 20%; }
    .col-price   { width: 20%; }

    .colm-row td {
        font-size: 13px;
        color: #000;
        padding: 14px 10px;
        border-bottom: 1px solid #ccc;
    }

    .border-left {
        border-left: 1px solid #ccc;
    }

    /* ===== QUOTATION BOTTOM ===== */
    .bottom-section {
        overflow: hidden;
        margin-top: 50px;
        margin-bottom: 20px;
        padding: 0 20px;
    }

    .footer-table {
        width: 320px;
        border-collapse: collapse;
        float: right;
    }

    .footer-table td {
        padding: 9px 8px;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 1px solid #ccc;
    }

    .footer-label {
        width: 230px;
        color: #333;
    }

    .footer-value {
        text-align: right;
        width: 90px;
        font-size: 15px;
        color: #2d3743;
        font-weight: 800;
    }

    /* ===== PAGE FOOTER - fixed at bottom ===== */
    .page-footer {
        background: #2d3743;
        padding: 30px 15px;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .footer-contact-table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-contact {
        color: #ffffff;
        font-size: 15px;
        font-weight: 300;
        text-align: center;
        padding: 0 10px;
        font-weight: bold;
    }
</style>

<body>

    <div class="container">

        {{-- ===================== HEADER ===================== --}}
        <div class="header">

            <table class="header-table">
                <tr>
                    {{-- Logo Cell --}}
                    <td class="logo-cell">
                        @php
                            $settings = \App\Models\Setting::first();
                            $logoPath = public_path('images/black-logo.png'); // Changed from assets/images
                            
                            if (file_exists($logoPath)) {
                                $logoData = base64_encode(file_get_contents($logoPath));
                                $logoSrc = 'data:image/png;base64,' . $logoData;
                            } else {
                                $logoSrc = '';
                            }
                        @endphp
                        
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" alt="Company Logo" style="width:150px; height:150px; object-fit:contain;">
                            {{-- <p class="tag-line">
                                We Generally Recycle
                                <!-- <i class="fas fa-recycle" style="color:black; font-weight:900;"></i> -->
                            </p> --}}
                        @endif
                    </td>

                    {{-- Info Cell --}}
                    <td class="info-cell">

                        {{-- QUOTATION Badge at top --}}
                        <div class="quotation-badge">
                            <span>QUOTATION</span>
                        </div>

                        {{-- Info Table below badge --}}
                        <table class="info-inner-table">
                            <tr>
                                <td class="label-cell">Date:</td>
                                <td class="value-cell">{{ $quotation->date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Quotation No:</td>
                                <td class="value-cell">{{ $quotation->quotation_number }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Company:</td>
                                <td class="value-cell">{{ $quotation->client->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Attention:</td>
                                <td class="value-cell">{{ $quotation->attention ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Quotation For:</td>
                                <td class="value-cell">{{ $quotation->quotation_for ?? 'N/A' }}</td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>

        </div>

        {{-- ===================== ADDRESS BAR ===================== --}}
        <div class="header-bottom">
            <p class="address-text">
                {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas lane, Borivali West. Mumbai - 400092' }}
            </p>
        </div>

        {{-- ===================== ITEMS TABLE ===================== --}}
        <div class="section-wrap">
            <table class="colm-table">
                <thead>
                    <tr class="colm-header">
                        <th class="col-product">PRODUCT, MATERIAL &amp; SIZE</th>
                        <th class="col-gsm">GSM</th>
                        <th class="col-price">BASIC PRICE (&#8377;)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotation->items as $item)
                    <tr class="colm-row">
                        <td class="col-product">{{ $item->particular }}</td>
                        <td class="col-gsm border-left">{{ $item->gsm ?? '-' }}</td>
                        <td class="col-price border-left">&#8377;{{ number_format($item->base_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr class="colm-row">
                        <td class="col-product">No items found</td>
                        <td class="col-gsm border-left">-</td>
                        <td class="col-price border-left">-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===================== INCLUSIONS ===================== --}}
        <div class="bottom-section">
            <table class="footer-table">
                <tr>
                    <td class="footer-label">Taxes:</td>
                    <td class="footer-value">{{ $quotation->is_tax_included ? 'Included' : '' }}</td>
                </tr>
                <tr>
                    <td class="footer-label">Delivery Charges:</td>
                    <td class="footer-value">{{ $quotation->is_delivery_charges_included ? 'Included' : '' }}</td>
                </tr>
                <tr>
                    <td class="footer-label">Printing:</td>
                    <td class="footer-value">{{ $quotation->is_printing_included ? 'Included' : '' }}</td>
                </tr>
                <tr>
                    <td class="footer-label">Plate &amp; Punch:</td>
                    <td class="footer-value">{{ $quotation->is_plate_and_punch ? 'Included' : '' }}</td>
                </tr>
                <tr>
                    <td class="footer-label">Lamination:</td>
                    <td class="footer-value">{{ $quotation->is_lamination ? 'Included' : '' }}</td>
                </tr>
            </table>
            <div style="clear:both;"></div>
        </div>

        @if($quotation->notes)
            <p style="margin-top:100px; text-align:center; font-size:13px; color:#000;">
                *{{ $quotation->notes ?? "" }}
            </p>
        @endif

        {{-- ===================== PAGE FOOTER ===================== --}}
        <div class="page-footer">
            <table class="footer-contact-table">
                <tr>
                    <td class="footer-contact">+{{ $settings->phone ?? '+91 9820006001' }}</td>
                    <td class="footer-contact">{{ $settings->email ?? 'boxmaker@myyahoo.com' }}</td>
                    <td class="footer-contact">{{ $settings->website_url ?? 'www.myboxmaker.com' }}</td>
                </tr>
            </table>
        </div>

    </div>

</body>
</html>