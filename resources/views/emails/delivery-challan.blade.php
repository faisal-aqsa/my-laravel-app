<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Challan Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        .message {
            color: #555;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .challan-details {
            background-color: #f8f9fa;
            border-left: 4px solid #2c3e50;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .challan-details h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .amount-highlight {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
        }
        .custom-message {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-style: italic;
            color: #856404;
        }
        .attachment-notice {
            background-color: #e8f5e9;
            border: 1px dashed #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .attachment-notice i {
            color: #4caf50;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .footer-message {
            color: #777;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }
        .contact-info {
            margin-top: 15px;
        }
        .contact-info p {
            margin: 5px 0;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-body {
                padding: 20px 15px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-value {
                margin-top: 5px;
                font-weight: 600;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🚚 Delivery Challan</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Dear {{ $challan->getClient->name ?? $challan->client->name ?? 'Valued Customer' }},
            </div>

            <div class="message">
                We hope this email finds you well. Please find attached the delivery challan for your recent order.
            </div>

            @if($customMessage)
            <div class="custom-message">
                <strong>Note:</strong> {{ $customMessage }}
            </div>
            @endif

            <!-- Challan Details -->
            <div class="challan-details">
                <h3>Delivery Challan Summary</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Challan Number:</span>
                    <span class="detail-value"><strong>{{ $challan->challan_number }}</strong></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Challan Date:</span>
                    <span class="detail-value">{{ $challan->challan_date->format('d M, Y') }}</span>
                </div>

                @if($challan->vehicle_no)
                <div class="detail-row">
                    <span class="detail-label">Vehicle Number:</span>
                    <span class="detail-value"><strong>{{ $challan->vehicle_no }}</strong></span>
                </div>
                @endif

                @if($challan->consignee_address)
                <div class="detail-row">
                    <span class="detail-label">Delivery Address:</span>
                    <span class="detail-value">{{ Str::limit($challan->consignee_address, 50) }}</span>
                </div>
                @endif

                <div class="detail-row" style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #2c3e50;">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value amount-highlight">₹{{ number_format($challan->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Items Summary (Optional) -->
            @if($challan->items && $challan->items->count() > 0)
            <div class="challan-details">
                <h3>Items Included ({{ $challan->items->count() }} items)</h3>
                @foreach($challan->items->take(5) as $item)
                <div class="detail-row">
                    <span class="detail-label">{{ $item->particular }}</span>
                    <span class="detail-value">{{ number_format($item->quantity, 2) }} × ₹{{ number_format($item->total_amount, 2) }}</span>
                </div>
                @endforeach
                @if($challan->items->count() > 5)
                <div class="detail-row">
                    <span class="detail-label" style="color: #666; font-style: italic;">
                        ... and {{ $challan->items->count() - 5 }} more items
                    </span>
                    <span class="detail-value"></span>
                </div>
                @endif
            </div>
            @endif

            <!-- Attachment Notice -->
            <div class="attachment-notice">
                <div style="font-size: 24px; margin-bottom: 10px;">📎</div>
                <strong>Delivery Challan PDF Attached</strong>
                <p style="margin: 5px 0 0 0; color: #666;">
                    Please find the detailed delivery challan attached to this email.
                </p>
            </div>

            <div class="footer-message">
                <p>
                    This delivery challan serves as proof of goods dispatched. Please verify the items upon receipt and contact us immediately if there are any discrepancies.
                </p>
                <p style="margin-top: 15px;">
                    <strong>Important:</strong><br>
                    Please keep this challan for your records and present it during delivery acceptance.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0 0 10px 0;">
                <strong>{{ config('app.name') }}</strong>
            </p>
            
            <div class="contact-info">
                {{-- Add your company contact information here --}}
                <p>Email: support@yourcompany.com</p>
                <p>Phone: +91 1234567890</p>
                <p>Website: www.yourcompany.com</p>
            </div>

            <p style="margin-top: 15px; color: #999; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>