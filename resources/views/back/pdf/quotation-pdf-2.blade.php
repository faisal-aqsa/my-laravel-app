<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation #{{ $quotation->quotation_number }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: Helvetica, Arial, sans-serif;
            color: #2d3743;
            font-size: 13px;
            background: #ffffff;
        }

        .page {

            position: relative;
            width: 100%;
            padding-bottom: 90px;
        }

        .watermark {

            position: absolute;
            top: 270px;
            left: 40px;
            font-size: 95px;
            font-weight: bold;
            color: #000;
            opacity: .035;
            letter-spacing: 10px;
            z-index: 0;
        }

        .container {

            position: relative;
            z-index: 2;
        }

        .top-bar {

            height: 10px;
            background: #ffbd59;
        }

        .header {

            background: #2d3743;
            color: #fff;
            padding: 35px 40px;
        }

        .header-table {

            width: 100%;
            border-collapse: collapse;
        }

        .header-left {

            width: 60%;
            vertical-align: top;
        }

        .header-right {

            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .logo {

            width: 130px;
        }

        .company {

            margin-top: 10px;
            font-size: 13px;
            letter-spacing: 4px;
            font-weight: bold;
        }

        .tagline {

            color: #ffbd59;
            font-size: 10px;
            letter-spacing: 3px;
            margin-top: 4px;
        }

        .quote-box {

            display: inline-block;
            background: #ffbd59;
            color: #2d3743;
            padding: 10px 20px;
            font-size: 28px;
            font-weight: bold;
            border-radius: 3px;
            margin-bottom: 15px;
        }

        .info-table {

            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        .info-table td {

            padding: 4px 0;
            font-size: 13px;
        }

        .info-label {

            color: #d2d2d2;
            width: 120px;
        }

        .info-value {

            font-weight: bold;
        }

        .section {

            padding: 30px 40px;
        }

        .cards {

            width: 100%;
            border-collapse: separate;
            border-spacing: 18px 0;
        }

        .card {

            width: 50%;
            border: 1px solid #e7e7e7;
            border-top: 6px solid #ffbd59;
            background: #fafafa;
            padding: 22px;
            vertical-align: top;
        }

        .card-title {

            color: #ffbd59;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .card-name {

            font-size: 17px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .card-text {

            line-height: 1.8;
            color: #666;
            font-size: 13px;
        }

        .section-title {

            margin-top: 30px;
            margin-bottom: 18px;
            border-left: 6px solid #ffbd59;
            padding-left: 14px;
            font-size: 18px;
            font-weight: bold;
        }

        .product-box {

            border: 1px solid #ececec;
            background: #fcfcfc;
            padding: 20px;
        }

        .product-table {

            width: 100%;
            border-collapse: collapse;
        }

        .product-table td {

            padding: 10px;
        }

        .product-label {

            color: #999;
            font-size: 12px;
            width: 140px;
        }

        .product-value {

            font-weight: bold;
            font-size: 14px;
        }

        .items-table {

            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table thead th {

            background: #2d3743;
            color: #ffbd59;
            padding: 14px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .items-table tbody td {

            padding: 15px;
            border-bottom: 1px solid #ececec;
        }

        .items-table tbody tr:nth-child(even) {

            background: #fafafa;
        }

        .center {

            text-align: center;
        }

        .right {

            text-align: right;
        }

        .gsm {

            background: #ffbd59;
            color: #2d3743;
            padding: 4px 10px;
            display: inline-block;
            font-weight: bold;
            border-radius: 20px;
            font-size: 11px;
        }

        .summary-box {

            width: 340px;
            border: 2px solid #ffbd59;
            float: right;
            margin-top: 30px;
        }

        .summary-header {

            background: #2d3743;
            color: #fff;
            padding: 12px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
        }

        .summary-table {

            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {

            padding: 12px 18px;
            border-bottom: 1px solid #ededed;
        }

        .summary-total {

            background: #ffbd59;
            font-size: 16px;
            font-weight: bold;
        }

        .notes {

            clear: both;
            margin-top: 50px;
            border-left: 5px solid #ffbd59;
            background: #fff9ec;
            padding: 20px;
        }

        .notes h3 {

            margin-bottom: 10px;
        }

        .signature {

            margin-top: 70px;
            text-align: right;
        }

        .sign-line {

            width: 240px;
            border-top: 2px solid #2d3743;
            display: inline-block;
            padding-top: 10px;
            font-weight: bold;
        }

        .footer {

            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #2d3743;
            border-top: 6px solid #ffbd59;
        }

        .footer table {

            width: 100%;
            border-collapse: collapse;
        }

        .footer td {

            color: #fff;
            text-align: center;
            padding: 18px;
            font-size: 12px;
        }

        .footer strong {

            color: #ffbd59;
        }
    </style>
</head>

<body>

    @php

        $settings = \App\Models\Setting::first();

        $logoPath = public_path('images/black-logo.png');

        $logoSrc = '';

        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

    @endphp

    <div class="page">

        <div class="watermark">

            BOXMAKER

        </div>

        <div class="container">

            <div class="top-bar"></div>

            <div class="header">

                <table class="header-table">

                    <tr>

                        <td class="header-left">

                            @if ($logoSrc)
                                <img src="{{ $logoSrc }}" class="logo">
                            @endif

                            <div class="company">
                                BOXMAKER
                            </div>

                            <div class="tagline">
                                PACKAGING • PRINTING • CUSTOM BOXES
                            </div>

                        </td>

                        <td class="header-right">

                            <div class="quote-box">

                                QUOTATION

                            </div>

                            <table class="info-table">

                                <tr>

                                    <td class="info-label">

                                        Quotation No

                                    </td>

                                    <td class="info-value">

                                        {{ $quotation->quotation_number }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">

                                        Date

                                    </td>

                                    <td class="info-value">

                                        {{ $quotation->date->format('M d, Y') }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">

                                        Valid Till

                                    </td>

                                    <td class="info-value">

                                        {{ isset($quotation->valid_until) ? $quotation->valid_until->format('M d, Y') : 'On Request' }}

                                    </td>

                                </tr>

                                @if ($quotation->attention)
                                    <tr>

                                        <td class="info-label">

                                            Attention

                                        </td>

                                        <td class="info-value">

                                            {{ $quotation->attention }}

                                        </td>

                                    </tr>
                                @endif

                            </table>

                        </td>

                    </tr>

                </table>

            </div>

            <div class="section">

                {{-- ===========================
     FROM / TO
============================ --}}

                <table class="cards">

                    <tr>

                        <td class="card">

                            <div class="card-title">

                                Quotation From

                            </div>

                            <div class="card-name">

                                BOXMAKER

                            </div>

                            <div class="card-text">

                                {{ $settings->address ?? '307, Sai Janak Classic, Near Flyover, Devidas Lane, Borivali West, Mumbai - 400092' }}

                                <br><br>

                                @if ($settings->gst_no)
                                    <b>GSTIN</b><br>

                                    {{ $settings->gst_no }}

                                    <br><br>
                                @endif

                                @if ($settings->phone)
                                    <b>Phone</b><br>

                                    {{ $settings->phone }}

                                    <br><br>
                                @endif

                                @if ($settings->email)
                                    <b>Email</b><br>

                                    {{ $settings->email }}
                                @endif

                            </div>

                        </td>

                        <td class="card">

                            <div class="card-title">

                                Quotation For

                            </div>

                            <div class="card-name">

                                {{ $quotation->client->name ?? 'N/A' }}

                            </div>

                            <div class="card-text">

                                @if ($quotation->client->factory_address ?? $quotation->client->address)
                                    {{ $quotation->client->factory_address ?? $quotation->client->address }}

                                    <br><br>
                                @endif

                                @if ($quotation->client->gst_no)
                                    <b>GSTIN</b><br>

                                    {{ $quotation->client->gst_no }}

                                    <br><br>
                                @endif

                                @if ($quotation->client->phone)
                                    <b>Phone</b><br>

                                    {{ $quotation->client->phone }}

                                    <br><br>
                                @endif

                                @if ($quotation->client->email)
                                    <b>Email</b><br>

                                    {{ $quotation->client->email }}
                                @endif

                                @if ($quotation->quotation_for)
                                    <br><br>

                                    <b>Requirement</b><br>

                                    {{ $quotation->quotation_for }}
                                @endif

                            </div>

                        </td>

                    </tr>

                </table>





                <div class="section-title">

                    PRODUCT DETAILS

                </div>

                <div class="product-box">

                    <table class="product-table">

                        <tr>

                            <td class="product-label">

                                Quotation Number

                            </td>

                            <td class="product-value">

                                {{ $quotation->quotation_number }}

                            </td>

                            <td class="product-label">

                                Quotation Date

                            </td>

                            <td class="product-value">

                                {{ $quotation->date->format('M d, Y') }}

                            </td>

                        </tr>

                        <tr>

                            <td class="product-label">

                                Customer

                            </td>

                            <td class="product-value">

                                {{ $quotation->client->name ?? 'N/A' }}

                            </td>

                            <td class="product-label">

                                Product

                            </td>

                            <td class="product-value">

                                {{ $quotation->quotation_for ?? 'Packaging Product' }}

                            </td>

                        </tr>

                    </table>

                </div>





                <div class="section-title">

                    ITEMS & PRICING

                </div>

                <table class="items-table">

                    <thead>

                        <tr>

                            <th width="50">

                                #

                            </th>

                            <th>

                                Particular

                            </th>

                            <th width="120">

                                GSM

                            </th>

                            <th width="170">

                                Basic Price

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($quotation->items as $index=>$item)
                            <tr>

                                <td class="center">

                                    {{ $index + 1 }}

                                </td>

                                <td>

                                    <div style="font-size:14px;font-weight:bold;color:#2d3743;">

                                        {{ $item->particular }}

                                    </div>

                                </td>

                                <td class="center">

                                    @if ($item->gsm)
                                        <span class="gsm">

                                            {{ $item->gsm }}

                                        </span>
                                    @else
                                        —
                                    @endif

                                </td>

                                <td class="right">

                                    <div style="font-size:15px;font-weight:bold;color:#2d3743;">

                                        ₹{{ number_format($item->base_price, 2) }}

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" style="padding:50px;text-align:center;color:#999;">

                                    No Items Available

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

                {{-- ===========================
     SUMMARY
============================ --}}

                <div class="summary-box">

                    <div class="summary-header">
                        QUOTATION SUMMARY
                    </div>

                    <table class="summary-table">

                        <tr>
                            <td>Subtotal</td>
                            <td align="right">
                                ₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Taxes</td>
                            <td align="right">
                                {{ $quotation->is_tax_included ? 'Included' : 'Extra' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Delivery Charges</td>
                            <td align="right">
                                {{ $quotation->is_delivery_charges_included ? 'Included' : 'Extra' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Printing</td>
                            <td align="right">
                                {{ $quotation->is_printing_included ? 'Included' : 'Extra' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Plate & Punch</td>
                            <td align="right">
                                {{ $quotation->is_plate_and_punch ? 'Included' : 'Extra' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Lamination</td>
                            <td align="right">
                                {{ $quotation->is_lamination ? 'Included' : 'Extra' }}
                            </td>
                        </tr>

                        <tr class="summary-total">

                            <td>
                                TOTAL
                            </td>

                            <td align="right">

                                ₹{{ number_format($quotation->items->sum(fn($i) => $i->base_price), 2) }}

                            </td>

                        </tr>

                    </table>

                </div>

                <div style="clear:both;"></div>


                @if ($quotation->notes)
                    <div class="notes">

                        <h3 style="color:#2d3743;">

                            Additional Notes

                        </h3>

                        <div style="line-height:28px;color:#666;">

                            {{ $quotation->notes }}

                        </div>

                    </div>
                @endif



                <table width="100%"
                    style="margin-top:45px;border-collapse:collapse;background:#fafafa;border:1px solid #eeeeee;">

                    <tr>

                        <td style="padding:18px;width:50%;vertical-align:top;">

                            <div style="font-size:12px;color:#999;margin-bottom:8px;">

                                PAYMENT TERMS

                            </div>

                            <div style="font-size:13px;line-height:24px;">

                                • Prices are in INR

                                <br>

                                • Taxes as applicable

                                <br>

                                • Freight extra unless mentioned

                                <br>

                                • Subject to final approval

                            </div>

                        </td>

                        <td style="padding:18px;width:50%;vertical-align:top;">

                            <div style="font-size:12px;color:#999;margin-bottom:8px;">

                                DELIVERY TERMS

                            </div>

                            <div style="font-size:13px;line-height:24px;">

                                • Delivery schedule after PO confirmation

                                <br>

                                • Artwork approval required

                                <br>

                                • Packaging suitable for transport

                                <br>

                                • Subject to stock availability

                            </div>

                        </td>

                    </tr>

                </table>



                <div style="margin-top:60px;text-align:center;">

                    <div style="font-size:24px;font-weight:bold;color:#2d3743;">

                        THANK YOU

                    </div>

                    <div style="margin-top:8px;color:#999;font-size:14px;">

                        We appreciate the opportunity to serve your packaging requirements.

                    </div>

                </div>




                <div class="signature">

                    <div style="font-size:12px;color:#999;margin-bottom:60px;">

                        Authorized by

                    </div>

                    <div class="sign-line">

                        FOR BOXMAKER

                        <br>

                        <span style="font-size:11px;color:#888;font-weight:normal;">

                            Authorised Signatory

                        </span>

                    </div>

                </div>





            </div>



            <div class="footer">

                <table>

                    <tr>

                        <td>

                            <strong>

                                {{ $settings->phone ?? '+91 9820006001' }}

                            </strong>

                        </td>

                        <td>

                            {{ $settings->email ?? 'boxmaker@myyahoo.com' }}

                        </td>

                        <td>

                            <strong>

                                {{ $settings->website_url ?? 'www.boxmaker.co.in' }}

                            </strong>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

</body>

</html>
