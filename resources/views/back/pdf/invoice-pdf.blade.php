<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="shortcut icon" href="/back/assets/images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        .container {
            width: 100%;
            min-height: 297mm;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-bottom: 150px; /* Space for fixed footer and signature */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header Section - Logo on LEFT, Details on RIGHT */
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

        .invoice-header-cell {
            width: 65%;
            vertical-align: top;
            padding: 0;
            border-left: 1px solid black;
        }

        .head {
            background: #000;
            text-align: center;
            font-size: 32px;
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
            font-weight: 600;
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
            padding: 15px 30px;
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

        /* Items Section - FULL WIDTH */
        .items-section {
            width: 100%;
            padding: 15px;
            flex: 1; /* This makes it grow to fill available space */
        }

        .items-header {
            background: #000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-header td {
            padding: 10px 10px;
            font-size: 15px;
        }

        .items-header .particular-col {
            width: 35%;
            padding-left: 20px;
            text-align: left;
        }

        .items-header .hsn-col {
            width: 15%;
            text-align: center;
        }

        .items-header .qty-col {
            width: 15%;
            text-align: center;
        }

        .items-header .rate-col {
            width: 15%;
            text-align: center;
        }

        .items-header .total-col {
            width: 20%;
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
            padding: 10px 10px;
            font-size: 15px;
        }

        .items-table .particular-col {
            width: 35%;
            padding-left: 20px;
            text-align: left;
        }

        .items-table .hsn-col {
            width: 15%;
            text-align: center;
            border-left: 1px solid #000;
        }

        .items-table .qty-col {
            width: 15%;
            text-align: center;
            border-left: 1px solid #000;
        }

        .items-table .rate-col {
            width: 15%;
            text-align: center;
            border-left: 1px solid #000;
        }

        .items-table .total-col {
            width: 20%;
            text-align: center;
            border-left: 1px solid #000;
        }

        /* Totals Section - FULL WIDTH */
        .totals-section {
            width: 100%;
            margin-top: 30px;
        }

        .totals-section td {
            vertical-align: top;
            padding: 0;
        }

        .words-cell {
            width: 45%;
            padding-right: 30px;
        }

        .words-cell h4 {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
            color: #000;
        }

        .words-cell p {
            font-size: 13px;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .words-cell h3 {
            font-size: 18px;
            font-weight: 500;
            color: #000;
            margin-top: 20px;
        }

        .amounts-cell {
            width: 45%;
        }

        .amounts-cell > table {
            width: 100%;
        }

        .subtotal-row {
            background: #000;
            color: #fff;
        }

        .subtotal-row td {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
        }

        .tax-rows-wrapper {
            padding: 0;
        }

        .tax-rows-inner {
            width: 100%;
        }

        .tax-rows-inner tr td {
            padding: 10px 20px;
            font-size: 15px;
            border-bottom: 1px solid #ddd;
        }

        .grand-total-row {
            background: #000;
            color: #fff;
            font-weight: bold;
        }

        .grand-total-row td {
            padding: 10px 20px;
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

        /* Alternative: Use absolute positioning for better PDF compatibility */
        .signature-container {
            position: absolute;
            bottom: 80px; /* Height of footer + extra spacing */
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
            font-size: 16px;
        }

        /* Page break settings for PDF */
        @page {
            margin: 0;
            size: A4 portrait;
        }

        /* Spacer to push content above fixed elements */
        .content-spacer {
            height: 180px; /* Space for signature and footer */
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section - Logo LEFT, Details RIGHT -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @php
                        $settings = \App\Models\Setting::first();
                        $logoPath = public_path('images/box-logo.png');
                        
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
                <td class="invoice-header-cell">
                    <div class="head">
                        @if($invoice->is_performa_invoice)
                            PROFORMA INVOICE
                        @else
                            TAX INVOICE
                        @endif
                    </div>
                    <table class="header-info-table">
                        <tr>
                            <td class="label-cell">Off:</td>
                            <td class="value-cell">{{ $settings->address ?? 'Address not available' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Date:</td>
                            <td class="value-cell">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Invoice No:</td>
                            <td class="value-cell">{{ $invoice->invoice_number }}</td>
                        </tr>
                        @if($invoice->vehicle_no)
                        <tr>
                            <td class="label-cell">Vehicle No:</td>
                            <td class="value-cell">{{ $invoice->vehicle_no }}</td>
                        </tr>
                        @endif
                        @if($invoice->po_number)
                        <tr>
                            <td class="label-cell">PO No:</td>
                            <td class="value-cell">{{ $invoice->po_number }}</td>
                        </tr>
                        @endif
                        @if($invoice->e_way_bill_no)
                        <tr>
                            <td class="label-cell">E-Way Bill No:</td>
                            <td class="value-cell">{{ $invoice->e_way_bill_no }}</td>
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
                        $client = $invoice->getClient;
                        $clientAddress = trim(preg_replace("/\s+/", " ", str_replace("\n", ", ", $client->address ?? '')));
                    @endphp

                    <p>
                        <strong>Buyer :</strong> {{ $client->name ?? 'N/A' }}<br>
                        <strong>Address :</strong> {{ $clientAddress ?: 'N/A' }}<br>
                        <strong>GST :</strong> {{ $client->gst_no ?: 'N/A' }}
                    </p>
                </td>

                <td class="consignee-cell">
                    <h2>CONSIGNEE SHIP TO:</h2>

                    @php
                        $consigneeAddress = $invoice->consignee_address
                            ? trim(preg_replace("/\s+/", " ", str_replace("\n", ", ", $invoice->consignee_address)))
                            : null;
                    @endphp

                    <p>
                        <strong>Consignee :</strong> {{ $invoice->getClient->name ?? 'N/A' }}<br>
                        <strong>Address :</strong> {{ $consigneeAddress ?? $clientAddress }}<br>
                        <strong>GST :</strong> {{ $invoice->getClient->gst_no ?? 'N/A' }}<br>
                    </p>
                </td>

            </tr>
        </table>

        <!-- Items Section -->
        <div class="items-section">
            <!-- Items Header -->
            <table class="items-header">
                <tr>
                    <td class="particular-col">DESCRIPTION</td>
                    <td class="hsn-col">HSN</td>
                    <td class="qty-col">QTY</td>
                    <td class="rate-col">RATE (₹)</td>
                    <td class="total-col">TOTAL (₹)</td>
                </tr>
            </table>

            <!-- Items List -->
            <table class="items-table">
                @foreach ($invoice->invoiceItems as $item)
                <tr>
                    <td class="particular-col">{{ $item->particular }}</td>
                    <td class="hsn-col">{{ $item->hsn_no ?? '-' }}</td>
                    <td class="qty-col">{{ number_format($item->quantity, 2) }}</td>
                    <td class="rate-col">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="total-col">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </table>

            <!-- Totals Section -->
            <table class="totals-section">
                <tr>
                    <td class="words-cell">
                        <h4>GRAND TOTAL IN WORDS</h4>
                        <p>
                            @php
                                function numberToWords($num) {
                                    $ones = array(
                                        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
                                        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                                        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
                                        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
                                        18 => 'Eighteen', 19 => 'Nineteen'
                                    );
                                    $tens = array(
                                        2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
                                        6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
                                    );
                                    
                                    if ($num < 20) {
                                        return $ones[$num];
                                    } elseif ($num < 100) {
                                        return $tens[floor($num / 10)] . (($num % 10 != 0) ? ' ' . $ones[$num % 10] : '');
                                    } elseif ($num < 1000) {
                                        return $ones[floor($num / 100)] . ' Hundred' . (($num % 100 != 0) ? ' ' . numberToWords($num % 100) : '');
                                    } elseif ($num < 100000) {
                                        return numberToWords(floor($num / 1000)) . ' Thousand' . (($num % 1000 != 0) ? ' ' . numberToWords($num % 1000) : '');
                                    } elseif ($num < 10000000) {
                                        return numberToWords(floor($num / 100000)) . ' Lakh' . (($num % 100000 != 0) ? ' ' . numberToWords($num % 100000) : '');
                                    } else {
                                        return numberToWords(floor($num / 10000000)) . ' Crore' . (($num % 10000000 != 0) ? ' ' . numberToWords($num % 10000000) : '');
                                    }
                                }
                                
                                function amountToWords($amount) {
                                    $rupees = floor($amount);
                                    $paise = round(($amount - $rupees) * 100);
                                    
                                    $words = numberToWords($rupees) . ' Rupees';
                                    if ($paise > 0) {
                                        $words .= ' and ' . numberToWords($paise) . ' Paise';
                                    }
                                    return $words . ' Only';
                                }
                            @endphp
                            {{ amountToWords($invoice->grand_total) }}
                        </p>
                    </td>
                    <td class="amounts-cell">
                        <table>
                            <tr class="subtotal-row">
                                <td>SUB-TOTAL</td>
                                <td style="text-align: right;">₹ {{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            <tr class="tax-rows-wrapper">
                                <td colspan="2" style="padding: 0;">
                                    <table class="tax-rows-inner">
                                        @if($invoice->is_sgst && $settings)
                                        <tr>
                                            <td>SGST ({{ $settings->sgst }}%)</td>
                                            <td style="text-align: right;">₹ {{ number_format(($invoice->total_amount * $settings->sgst) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($invoice->is_cgst && $settings)
                                        <tr>
                                            <td>CGST ({{ $settings->cgst }}%)</td>
                                            <td style="text-align: right;">₹ {{ number_format(($invoice->total_amount * $settings->cgst) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($invoice->is_igst && $settings)
                                        <tr>
                                            <td>IGST ({{ $settings->igst }}%)</td>
                                            <td style="text-align: right;">₹ {{ number_format(($invoice->total_amount * $settings->igst) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if(!$invoice->is_sgst && !$invoice->is_cgst && !$invoice->is_igst)
                                        <tr>
                                            <td>No Tax Applied</td>
                                            <td style="text-align: right;">-</td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                            <tr class="grand-total-row">
                                <td>Grand Total</td>
                                <td style="text-align: right;">₹ {{ number_format($invoice->grand_total, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Spacer to push content above fixed elements -->
        <div class="content-spacer"></div>

        <!-- Signature Section (Fixed above footer) -->
        <div class="signature-container">
            <div style="float:left; width:50%; padding-left:30px; box-sizing:border-box;">
                <p style="margin:0 0 25px 0; font-size:12px; text-align:left;">For, BOXMAKER</p>
                <div style="border-top:1px solid #000; width:140px; margin:0;"></div>
                <p style="margin:5px 0 0 0; font-size:12px; font-weight: bold;">PARTNERS</p>
            </div>
            
            <div style="clear:both;"></div>
            
            <p style="margin-top:10px; text-align:center; font-size:13px; color:#000;">
                *This is a computer generated invoice.
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