<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Email</title>
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
            background: linear-gradient(135deg, #1a6b3c 0%, #28a745 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header p {
            margin: 8px 0 0 0;
            font-size: 15px;
            opacity: 0.9;
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
        .quotation-details {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .quotation-details h3 {
            margin-top: 0;
            color: #1a6b3c;
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
            text-align: right;
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
        .validity-notice {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .validity-notice p {
            margin: 0;
            color: #0d47a1;
            font-size: 14px;
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
            body { padding: 10px; }
            .email-body { padding: 20px 15px; }
            .detail-row { flex-direction: column; }
            .detail-value { margin-top: 5px; font-weight: 600; text-align: left; }
        }
    </style>
</head>
<body>
    <div class="email-container">

        <!-- Header -->
        <div class="email-header">
            <h1>📋 Quotation</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">

            <div class="greeting">
                Dear {{ $quotation->client->name ?? 'Valued Customer' }},
            </div>

            <div class="message">
                Thank you for your interest. Please find attached the quotation as requested. We hope this meets your requirements and look forward to the opportunity of working with you.
            </div>

            @if($customMessage)
            <div class="custom-message">
                <strong>Note:</strong> {{ $customMessage }}
            </div>
            @endif

            <!-- Quotation Details -->
            <div class="quotation-details">
                <h3>📋 Quotation Summary</h3>

                <div class="detail-row">
                    <span class="detail-label">Quotation Date:</span>
                    <span class="detail-value">{{ $quotation->date->format('d M, Y') }}</span>
                </div>

                @if($quotation->attention)
                <div class="detail-row">
                    <span class="detail-label">Attention:</span>
                    <span class="detail-value">{{ $quotation->attention }}</span>
                </div>
                @endif

                @if($quotation->quotation_for)
                <div class="detail-row">
                    <span class="detail-label">Quotation For:</span>
                    <span class="detail-value">{{ $quotation->quotation_for }}</span>
                </div>
                @endif

                @if($quotation->items && $quotation->items->count() > 0)
                <div class="detail-row">
                    <span class="detail-label">Total Items:</span>
                    <span class="detail-value">{{ $quotation->items->count() }} item(s)</span>
                </div>
                @endif
            </div>

            <!-- Validity Notice -->
            <div class="validity-notice">
                <p>
                    <strong>📌 Please Note:</strong> This quotation is valid for a limited period. 
                    Please review the attached PDF for detailed pricing, terms & conditions.
                </p>
            </div>

            <!-- Attachment Notice -->
            <div class="attachment-notice">
                <div style="font-size: 24px; margin-bottom: 10px;">📎</div>
                <strong>Quotation PDF Attached</strong>
                <p style="margin: 5px 0 0 0; color: #666;">
                    Please find the detailed quotation with pricing attached to this email.
                </p>
            </div>

            <div class="footer-message">
                <p>
                    To accept this quotation or for any queries, please feel free to contact us. 
                    We would be happy to discuss and accommodate your specific requirements.
                </p>
                <p style="margin-top: 15px;">
                    <strong>Next Steps:</strong><br>
                    Please review the attached quotation and let us know if you'd like to proceed or if you have any questions.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0 0 10px 0;">
                <strong>{{ config('app.name') }}</strong>
            </p>

            <div class="contact-info">
                {{-- Update with your company contact details --}}
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