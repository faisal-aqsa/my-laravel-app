<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Email</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .invoice-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .invoice-details h3 {
            margin-top: 0;
            color: #667eea;
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
            color: #667eea;
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
            <h1>📄 Invoice</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Dear {{ $invoice->getClient->name }},
            </div>

            <div class="message">
                We hope this email finds you well. Please find attached the invoice for your recent transaction with us.
            </div>

            @if($customMessage)
            <div class="custom-message">
                <strong>Note:</strong> {{ $customMessage }}
            </div>
            @endif

            <!-- Invoice Details -->
            <div class="invoice-details">
                <h3>Invoice Summary</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Invoice Number:</span>
                    <span class="detail-value"><strong>{{ $invoice->invoice_number }}</strong></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Invoice Date:</span>
                    <span class="detail-value">{{ $invoice->invoice_date->format('d M, Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Due Date:</span>
                    <span class="detail-value">{{ $invoice->due_date->format('d M, Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <strong style="color: 
                            @if($invoice->status === 'paid') #28a745
                            @elseif($invoice->status === 'partial_paid') #ffc107
                            @elseif($invoice->status === 'overdue') #dc3545
                            @else #6c757d
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                        </strong>
                    </span>
                </div>

                <div class="detail-row" style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #667eea;">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value amount-highlight">₹{{ number_format($invoice->grand_total, 2) }}</span>
                </div>

                @if($invoice->paid_amount > 0)
                <div class="detail-row">
                    <span class="detail-label">Paid Amount:</span>
                    <span class="detail-value">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Remaining Amount:</span>
                    <span class="detail-value" style="color: #dc3545; font-weight: 600;">
                        ₹{{ number_format($invoice->grand_total - $invoice->paid_amount, 2) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Attachment Notice -->
            <div class="attachment-notice">
                <div style="font-size: 24px; margin-bottom: 10px;">📎</div>
                <strong>Invoice PDF Attached</strong>
                <p style="margin: 5px 0 0 0; color: #666;">
                    Please find the detailed invoice attached to this email.
                </p>
            </div>

            <div class="footer-message">
                <p>
                    If you have any questions about this invoice, please don't hesitate to contact us. 
                    We appreciate your business and look forward to serving you again.
                </p>
                <p style="margin-top: 15px;">
                    <strong>Payment Instructions:</strong><br>
                    Please make the payment before the due date to avoid any late fees.
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