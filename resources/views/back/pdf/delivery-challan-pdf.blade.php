<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Delivery Challan #{{ $challan->challan_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #fff;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .page-wrapper {
            width: 100%;
            height: 297mm;
            /* border: 1px solid black; */
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            padding-bottom: 160px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header Section - Logo LEFT, Details RIGHT */
        .header-table {
            width: 100%;
        }

        .logo-cell {
            width: 35%;
            text-align: center;
            vertical-align: middle;
            padding: 30px 20px;
        }

        .logo-cell img {
            max-width: 120px;
            max-height: 100px;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .logo-cell p {
            font-size: 15px;
            font-weight: normal;
            margin-top: 10px;
        }

        .challan-header-cell {
            width: 65%;
            vertical-align: top;
            padding: 0;
            border-left: 1px solid black;
        }

        .head {
            background: #000;
            text-align: center;
            font-size: 28px;
            color: #fff;
            padding: 25px 0;
            font-weight: bold;
            letter-spacing: 8px;
        }

        .header-info-table {
            width: 100%;
            margin: 0;
        }

        .header-info-table td {
            padding: 10px 30px;
            font-size: 15px;
        }

        .header-info-table .label-cell {
            width: 30%;
            font-weight: 500;
            text-align: left;
        }

        .header-info-table .value-cell {
            width: 70%;
            text-align: right;
            font-weight: normal;
        }

        /* Buyer and Consignee Section */
        .buyer-consignee-table {
            width: 100%;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .buyer-consignee-table td {
            width: 50%;
            padding: 20px 30px;
            vertical-align: top;
        }

        .buyer-consignee-table .consignee-cell {
            border-left: 1px solid black;
        }

        .buyer-consignee-table h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #000;
        }

        .buyer-consignee-table p {
            font-size: 14px;
            margin-bottom: 6px;
            line-height: 1.5;
        }

        /* Items Section */
        .items-section {
            width: 100%;
            padding: 30px;
        }

        .items-header {
            background: #000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-header td {
            padding: 15px 10px;
            font-size: 15px;
        }

        .items-header .particular-col {
            width: 50%;
            padding-left: 20px;
            text-align: left;
        }

        .items-header .qty-col {
            width: 20%;
            text-align: center;
        }

        .items-header .total-col {
            width: 30%;
            text-align: center;
        }

        .items-table {
            width: 100%;
            margin-top: 0;
        }

        .items-table tr {
            border-bottom: 1px solid #000;
        }

        .items-table td {
            padding: 15px 10px;
            font-size: 15px;
        }

        .items-table .particular-col {
            width: 50%;
            padding-left: 20px;
            text-align: left;
        }

        .items-table .qty-col {
            width: 20%;
            text-align: center;
            border-left: 1px solid #000;
        }

        .items-table .total-col {
            width: 30%;
            text-align: center;
            border-left: 1px solid #000;
        }

        /* Total Section */
        .total-section {
            width: 100%;
            margin-top: 30px;
        }

        .total-section td {
            vertical-align: top;
            padding: 0;
        }

        .company-cell {
            width: 50%;
            padding-right: 30px;
        }

        .company-cell h3 {
            font-size: 18px;
            font-weight: 500;
            color: #000;
            margin-top: 60px;
        }

        .total-cell {
            width: 50%;
        }

        .total-cell > table {
            width: 100%;
        }

        .total-row {
            background: #000;
            color: #fff;
            font-weight: bold;
        }

        .total-row td {
            padding: 15px 20px;
            font-size: 17px;
            font-weight: bold;
        }

        /* Signature */
        .signature-wrapper {
            position: absolute;
            bottom: 90px; /* sits just above footer */
            left: 30px;
            right: 30px;
        }

        .signature-footer {
            position: fixed;
            bottom: 70px; /* JUST ABOVE FOOTER */
            left: 30px;
            right: 30px;
        }

        .signature-line {
            width: 220px;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 6px;
            font-size: 14px;
            font-weight: 500;
        }


        .signature-section {
            border-top: 1px solid #000;
            text-align: center;
            width: 220px;
            display: inline-block;
        }

        .signature-section p {
            font-size: 14px;
            padding-top: 6px;
            font-weight: 500;
        }


        /* Footer - ABSOLUTE POSITION AT BOTTOM */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #000;
            color: #fff;
            padding: 20px 30px;
        }

        .footer td {
            padding: 8px 15px;
            font-size: 17px;
        }

        .footer .center-cell {
            border-left: 1px solid #fff;
            border-right: 1px solid #fff;
            text-align: center;
            width: 34%;
        }

        .footer .left-cell {
            text-align: left;
            width: 33%;
        }

        .footer .right-cell {
            text-align: right;
            width: 33%;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="content-wrapper">
            <!-- Header Section -->
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        @php
                            $settings = \App\Models\Setting::first();
                        @endphp
                        <img src="{{ public_path('assets/images/box-logo.png') }}" alt="Company Logo">
                        <p>GST: {{ $settings->gst_no ?? 'N/A' }}</p>
                    </td>
                    <td class="challan-header-cell">
                        <div class="head">DELIVERY CHALLAN</div>
                        <table class="header-info-table">
                            <tr>
                                <td class="label-cell">Off:</td>
                                <td class="value-cell">{{ $settings->address ?? 'Address not available' }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Date:</td>
                                <td class="value-cell">{{ $challan->challan_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="label-cell">Challan No:</td>
                                <td class="value-cell">{{ $challan->challan_number }}</td>
                            </tr>
                            @if($challan->vehicle_no)
                            <tr>
                                <td class="label-cell">Vehicle No.:</td>
                                <td class="value-cell">{{ $challan->vehicle_no }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Buyer and Consignee Section -->
            <table class="buyer-consignee-table">
                <tr>
                    <td>
                        <h2>BUYER:</h2>
                        <!-- <p>{{ $challan->client->name ?? 'N/A' }}</p>
                        @php
                            $clientAddress = $challan->client->address ?? '';
                            $addressLines = explode("\n", $clientAddress);
                        @endphp
                        @foreach($addressLines as $line)
                            @if(trim($line))
                                <p>{{ trim($line) }}</p>
                            @endif
                        @endforeach
                        @if($challan->client->gst_no)
                            <p>GST: {{ $challan->client->gst_no }}</p>
                        @endif -->

                        @php
                            $client = $challan->getClient;
                            $clientAddress = trim(preg_replace("/\s+/", " ", str_replace("\n", ", ", $client->address ?? '')));
                        @endphp

                        <p>
                            <strong>Buyer :</strong> {{ $client->name ?? 'N/A' }}<br>
                            <strong>Address :</strong> {{ $clientAddress ?: 'N/A' }}<br>
                            <strong>GST :</strong> {{ $client->gst_no ?: 'N/A' }}
                        </p>
                    </td>
                    <td class="consignee-cell">
                        <h2>CONSIGNEE:</h2>

                        @php
                            $consigneeAddress = $challan->consignee_address
                                ? trim(preg_replace("/\s+/", " ", str_replace("\n", ", ", $challan->consignee_address)))
                                : null;
                        @endphp

                        <p>
                            <strong>Consignee :</strong> {{ $challan->getClient->name ?? 'N/A' }}<br>
                            <strong>Address :</strong> {{ $consigneeAddress ?? $clientAddress }}<br>
                            <strong>GST :</strong> {{ $challan->getClient->gst_no ?? 'N/A' }}<br>
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Items Section -->
            <div class="items-section">
                <!-- Items Header -->
                <table class="items-header">
                    <tr>
                        <td class="particular-col">PARTICULAR</td>
                        <td class="qty-col">QTY</td>
                        <td class="total-col">TOTAL (₹)</td>
                    </tr>
                </table>

                <!-- Items List -->
                <table class="items-table">
                    @foreach ($challan->items as $item)
                    <tr>
                        <td class="particular-col">{{ $item->particular }}</td>
                        <td class="qty-col">{{ number_format($item->quantity, 2) }}</td>
                        <td class="total-col">{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </table>

                <!-- Total Section -->
                <table class="total-section">
                    <tr>
                        <td class="company-cell">
                            <!-- <h3>For, {{ $settings->name ?? 'Company Name' }}</h3> -->
                        </td>
                        <td class="total-cell">
                            <table>
                                <tr class="total-row">
                                    <td>Total Amount</td>
                                    <td style="text-align: right;">₹ {{ number_format($challan->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signature Section (Attached to Footer) -->
        <!-- <table width="100%" style="margin-top:60px;">
            <tr>
                <td width="50%" style="text-align:left; vertical-align:bottom;">
                    <div style="border-top:1px solid #000; width:220px;"></div>
                    <p style="margin-top:5px;">STAMP & SIGN</p>
                </td>

                <td width="50%" style="text-align:right; vertical-align:bottom;">
                    <p style="margin-bottom:30px;">Your Sincerely</p>
                    <div style="border-top:1px solid #000; width:220px; margin-left:auto;"></div>
                    <p style="margin-top:5px;">BoxMaker</p>
                </td>
            </tr>
        </table> -->



        <!-- Footer - ABSOLUTE POSITION AT BOTTOM -->
        <table class="footer">
            <tr>
                <td class="left-cell">{{ $settings->website_url ?? 'www.company.com' }}</td>
                <td class="center-cell">{{ $settings->phone ?? '+91 XXXXXXXXXX' }}</td>
                <td class="right-cell">{{ $settings->email ?? 'email@company.com' }}</td>
            </tr>
        </table>
    </div>
</body>

</html>