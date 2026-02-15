<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Delivery Challan #{{ $challan->challan_number }}</title>
    <link rel="shortcut icon" href="/back/assets/images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
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
            min-height: 100vh;
            position: relative;
        }

        .page-wrapper {
            width: 100%;
            min-height: 297mm;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-bottom: 120px; /* Space for fixed footer */
        }

        .content-wrapper {
            flex: 1;
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
            padding: 30px 20px;
            position: relative;
            vertical-align: top;
        }

        .logo-cell img {
            max-width: 170px;
            max-height: 170px;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .logo-cell .gst-p {
            position: absolute;
            bottom: 0;
            padding: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 15px;
            margin: 0;
            background-color: #000;
            color: #ffffff;
            font-weight: bold;
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
        }

        .header-info-table {
            width: 100%;
            margin: 0;
        }

        .header-info-table td {
            padding: 5px 20px;
            font-size: 15px;
        }

        .header-info-table .label-cell {
            width: 35%;
            font-weight: 700;
            text-align: left;
        }

        .header-info-table .value-cell {
            width: 65%;
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
            padding: 15px;
        }

        .items-header {
            background: #000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-header td {
            padding: 15px 5px;
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
            padding: 15px 5px;
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

        /* Fixed Signature Section - positioned above footer */
        .fixed-signature-section {
            position: fixed;
            bottom: 100px; /* Height of footer + spacing */
            left: 0;
            right: 0;
            background: white;
            z-index: 100;
            padding: 0 30px;
        }

        /* Alternative: If fixed positioning causes issues in PDF, use absolute */
        .signature-container {
            position: absolute;
            bottom: 80px; /* Height of footer + spacing */
            left: 0;
            right: 0;
            background: white;
            padding: 0 5px;
        }

        /* Footer - FIXED AT BOTTOM */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #000;
            color: #fff;
            padding: 20px 30px;
            z-index: 1000;
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

        .tag-line {
            margin-top:8px; 
            font-weight:bold; 
            font-style:italic; 
            font-family: 'DejaVu Sans', Arial, sans-serif;
            display:flex; 
            align-items:center; 
            gap:6px;
            font-size: 20px; 
        }

        /* Ensure content doesn't overlap with fixed elements */
        .content-spacer {
            height: 200px; /* Space for signature and footer */
            width: 100%;
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
                            $logoPath = public_path('images/box-logo.png'); // Changed from assets/images
                            
                            if (file_exists($logoPath)) {
                                $logoData = base64_encode(file_get_contents($logoPath));
                                $logoSrc = 'data:image/png;base64,' . $logoData;
                            } else {
                                $logoSrc = '';
                            }
                        @endphp
                        
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" alt="Company Logo" style="width:200px; height:200px; object-fit:contain;">
                            <p class="tag-line">
                                We Generally Recycle
                                <i class="fas fa-recycle" style="color:black; font-weight:900;"></i>
                            </p>
                        @endif
                        <p class="gst-p">GST: {{ $settings->gst_no ?? 'N/A' }}</p>
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
                                    <td class="label-cell">Vehicle No:</td>
                                    <td class="value-cell">{{ $challan->vehicle_no }}</td>
                                </tr>
                            @endif
                            @if($challan->delivery_partner_phone)
                                <tr>
                                    <td class="label-cell">Delivery Partner Contact No:</td>
                                    <td class="value-cell">{{ $challan->delivery_partner_phone }}</td>
                                </tr>
                            @endif
                            @if($challan->vehicle_no)
                                <tr>
                                    <td class="label-cell" style="color:#fff !important;">Vehicle No:</td>
                                    <td class="value-cell" style="color:#fff !important;">{{ $challan->vehicle_no }}</td>
                                </tr>
                            @endif
                            @if($challan->vehicle_no)
                                <tr>
                                    <td class="label-cell" style="color:#fff !important;">Vehicle No:</td>
                                    <td class="value-cell" style="color:#fff !important;">{{ $challan->vehicle_no }}</td>
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
                        <!-- <td class="total-col">TOTAL (₹)</td> -->
                    </tr>
                </table>

                <!-- Items List -->
                <table class="items-table">
                    @foreach ($challan->items as $item)
                    <tr>
                        <td class="particular-col">{{ $item->particular }}</td>
                        <td class="qty-col">{{ number_format($item->quantity, 2) }}</td>
                        <!-- <td class="total-col">{{ number_format($item->total_amount, 2) }}</td> -->
                    </tr>
                    @endforeach
                </table>

                <!-- Total Section -->
                <table class="total-section">
                    <tr>
                        <td class="company-cell">
                            <!-- <h3>For, {{ $settings->name ?? 'Company Name' }}</h3> -->
                        </td>
                        <!-- <td class="total-cell">
                            <table>
                                <tr class="total-row">
                                    <td>Total Amount</td>
                                    <td style="text-align: right;">₹ {{ number_format($challan->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </td> -->
                    </tr>
                </table>
            </div>
            
            <!-- Spacer to push content above fixed elements -->
            <div class="content-spacer"></div>
        </div>

        <!-- Signature Section (Fixed above footer) -->
        <div class="signature-container">
            <div style="float:left; width:50%; padding-left:30px; box-sizing:border-box;">
                <p style="margin:0 0 25px 0; font-size:12px; text-align:right; color: #fff;">Your Sincerely</p>
                <div style="border-top:1px solid #000; width:140px; margin:0;"></div>
                <p style="margin:5px 0 0 0; font-size:12px; font-weight: bold;">STAMP & SIGN</p>
            </div>

            <div style="float:right; width:50%; padding-right:30px; box-sizing:border-box;">
                <p style="margin:0 0 25px 0; font-size:12px; text-align:right;">Your Sincerely</p>
                <div style="border-top:1px solid #000; width:140px; margin:0 0 0 auto;"></div>
                <p style="margin:5px 0 0 0; font-size:12px; text-align:right; font-weight: bold;">BOXMAKER</p>
            </div>
            
            <div style="clear:both;"></div>
            
            <!-- Computer Generated Note -->
            <p style="margin-top:20px; text-align:center; font-size:13px; color:#000;">
                *This is a computer generated delivery challan.
            </p>
        </div>

        <!-- Footer - FIXED AT BOTTOM -->
        <table class="footer">
            <tr>
                <td class="left-cell">{{ $settings->website_url ?? 'www.company.com' }}</td>
                <td class="center-cell">+{{ $settings->phone ?? '+91 XXXXXXXXXX' }}</td>
                <td class="right-cell">{{ $settings->email ?? 'email@company.com' }}</td>
            </tr>
        </table>
    </div>
</body>

</html>