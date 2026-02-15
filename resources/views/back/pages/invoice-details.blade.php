@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Invoice Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                @if($invoice->is_performa_invoice)
                    <span class="badge bg-warning text-dark me-2">PROFORMA</span>
                @endif
                Invoice #{{ $invoice->invoice_number }}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.all-invoices') }}">Invoices</a></li>
                    <li class="breadcrumb-item active">Invoice Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toasts -->
<div id="success" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
    <div class="toast-header bg-success text-white">
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"></div>
</div>

<div id="danger" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
    <div class="toast-header bg-danger text-white">
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"></div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 justify-content-end"></div>
                    <!-- Back -->
                    <a href="{{ route('admin.all-invoices') }}" 
                    class="btn btn-secondary waves-effect waves-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Invoices
                    </a>

                    <!-- Edit -->
                    <a href="{{ route('admin.edit-invoice', ['id' => $invoice->id]) }}" 
                    class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-edit me-2"></i>Edit Invoice
                    </a>

                    <!-- Update Payment -->
                    <button type="button" 
                        class="btn btn-success waves-effect waves-light payment-btn"
                        data-invoice-id="{{ $invoice->id }}"
                        data-paid-amount="{{ $invoice->paid_amount ?? 0 }}"
                        data-grand-total="{{ $invoice->grand_total }}">
                        <i class="fas fa-credit-card me-2"></i>Update Payment
                    </button>

                    <!-- Email -->
                    <button type="button" 
                        class="btn btn-info waves-effect waves-light email-invoice-btn"
                        data-invoice-id="{{ $invoice->id }}"
                        data-client-name="{{ $invoice->getClient->name ?? 'N/A' }}"
                        data-client-email="{{ $invoice->getClient->email ?? '' }}"
                        data-invoice-number="{{ $invoice->invoice_number }}"
                        data-grand-total="{{ $invoice->grand_total }}"
                        data-status="{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}">
                        <i class="fas fa-envelope me-2"></i>Email Invoice
                    </button>

                    <!-- Download PDF (Changed Color) -->
                    <a href="{{ route('admin.invoice-download', $invoice->id) }}" 
                    class="btn btn-dark waves-effect waves-light">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>

                    <!-- Print -->
                    <button type="button" 
                        class="btn btn-warning waves-effect waves-light print-invoice-btn">
                        <i class="fas fa-print me-2"></i>Print
                    </button>

                </div>
            </div>
        </div>

        <!-- Invoice Details Card -->
        <div class="card" id="invoice-print-area">
            <div class="card-body">
                <!-- Header with Logo and Title -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            @php
                                $settings = \App\Models\Setting::first();
                            @endphp
                            @if($settings && $settings->logo)
                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Company Logo" height="60" class="me-3">
                            @endif
                            <div>
                                <h4 class="font-size-24 mb-1 fw-bold">BOXMAKER</h4>
                                <p class="text-muted mb-0">{{ $settings->address ?? '' }}</p>
                                <p class="text-muted mb-0">GST: {{ $settings->gst_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <div>
                            <h4 class="font-size-20">
                                @if($invoice->is_performa_invoice)
                                    <span class="badge bg-warning text-dark">PROFORMA INVOICE</span>
                                @else
                                    <span class="badge bg-primary">TAX INVOICE</span>
                                @endif
                            </h4>
                            <h5 class="font-size-16 mt-2">Invoice #{{ $invoice->invoice_number }}</h5>
                            <p class="text-muted mb-0">Date: {{ $invoice->invoice_date->format('d M, Y') }}</p>
                            <p class="text-muted mb-0">Due Date: {{ $invoice->due_date->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Client and Company Details -->
                <div class="row">
                    <!-- Bill To -->
                    <div class="col-sm-6">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Bill To</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="font-size-16 mb-2">{{ $invoice->getClient->name ?? 'N/A' }}</h5>
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                    {{ $invoice->getClient->factory_address ?? 'No address provided' }}
                                </p>
                                @if($invoice->getClient->gst_no)
                                    <p class="mb-1">
                                        <i class="fas fa-id-card me-2 text-muted"></i>
                                        GST: {{ $invoice->getClient->gst_no }}
                                    </p>
                                @endif
                                @if($invoice->getClient->email)
                                    <p class="mb-1">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        {{ $invoice->getClient->email }}
                                    </p>
                                @endif
                                @if($invoice->getClient->phone)
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $invoice->getClient->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping To -->
                    <div class="col-sm-6">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Ship To</h6>
                            </div>
                            <div class="card-body">
                                @if($invoice->consignee_address)
                                    <p class="mb-3">{{ $invoice->consignee_address }}</p>
                                @else
                                    <p class="text-muted mb-3">Same as billing address</p>
                                @endif
                                
                                <div class="row">
                                    @if($invoice->po_number)
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>PO Number:</strong><br>
                                            <span class="text-muted">{{ $invoice->po_number }}</span>
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->vehicle_no)
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Vehicle No:</strong><br>
                                            <span class="text-muted">{{ $invoice->vehicle_no }}</span>
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->e_way_bill_no)
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>E-Way Bill No:</strong><br>
                                            <span class="text-muted">{{ $invoice->e_way_bill_no }}</span>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items Table -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Invoice Items</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="35%">Particular</th>
                                                <th width="15%">HSN No</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Unit Price (₹)</th>
                                                <th width="15%">Total (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoiceItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->particular }}</td>
                                                <td>{{ $item->hsn_no ?? 'N/A' }}</td>
                                                <td>{{ number_format($item->quantity, 2) }}</td>
                                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-end">₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Summary -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <!-- Tax Information Card -->
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-percentage me-2"></i>Tax Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($invoice->is_sgst || $invoice->is_cgst)
                                        <div class="col-6">
                                            <p class="mb-2">
                                                <span class="badge bg-primary me-2">SGST</span>
                                                <span class="fw-bold">{{ $invoice->sgst_rate ?? $settings->sgst }}%</span>
                                            </p>
                                            <p class="mb-2">
                                                <span class="badge bg-success me-2">CGST</span>
                                                <span class="fw-bold">{{ $invoice->cgst_rate ?? $settings->cgst }}%</span>
                                            </p>
                                            <p class="text-muted small">
                                                <i class="fas fa-info-circle me-1"></i>Intra-State Supply
                                            </p>
                                        </div>
                                    @endif
                                    
                                    @if($invoice->is_igst)
                                        <div class="col-6">
                                            <p class="mb-2">
                                                <span class="badge bg-info me-2">IGST</span>
                                                <span class="fw-bold">{{ $invoice->igst_rate ?? $settings->igst }}%</span>
                                            </p>
                                            <p class="text-muted small">
                                                <i class="fas fa-info-circle me-1"></i>Inter-State Supply
                                            </p>
                                        </div>
                                    @endif

                                    @if(!$invoice->is_sgst && !$invoice->is_cgst && !$invoice->is_igst)
                                        <div class="col-12">
                                            <p class="text-muted mb-0">No tax applied</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Amount Summary Card -->
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Amount Summary</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td width="60%"><strong>Subtotal:</strong></td>
                                            <td class="text-end">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                        </tr>
                                        
                                        @if($invoice->is_sgst)
                                        <tr>
                                            <td><strong>SGST ({{ $invoice->sgst_rate ?? $settings->sgst }}%):</strong></td>
                                            <td class="text-end">₹{{ number_format(($invoice->total_amount * ($invoice->sgst_rate ?? $settings->sgst)) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        
                                        @if($invoice->is_cgst)
                                        <tr>
                                            <td><strong>CGST ({{ $invoice->cgst_rate ?? $settings->cgst }}%):</strong></td>
                                            <td class="text-end">₹{{ number_format(($invoice->total_amount * ($invoice->cgst_rate ?? $settings->cgst)) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        
                                        @if($invoice->is_igst)
                                        <tr>
                                            <td><strong>IGST ({{ $invoice->igst_rate ?? $settings->igst }}%):</strong></td>
                                            <td class="text-end">₹{{ number_format(($invoice->total_amount * ($invoice->igst_rate ?? $settings->igst)) / 100, 2) }}</td>
                                        </tr>
                                        @endif
                                        
                                        <tr class="table-active">
                                            <td><strong>Grand Total:</strong></td>
                                            <td class="text-end"><strong>₹{{ number_format($invoice->grand_total, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-2">Grand Total:</p>
                                        <p class="mb-2">Paid Amount:</p>
                                        <p class="mb-2">Remaining:</p>
                                        <p class="mb-0">Status:</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="mb-2 fw-bold">₹{{ number_format($invoice->grand_total, 2) }}</p>
                                        <p class="mb-2 fw-bold text-success">₹{{ number_format($invoice->paid_amount ?? 0, 2) }}</p>
                                        <p class="mb-2 fw-bold {{ ($invoice->grand_total - ($invoice->paid_amount ?? 0)) > 0 ? 'text-danger' : 'text-success' }}">
                                            ₹{{ number_format($invoice->grand_total - ($invoice->paid_amount ?? 0), 2) }}
                                        </p>
                                        <p class="mb-0">
                                            @php
                                                $statusClass = '';
                                                switch($invoice->status) {
                                                    case 'paid': $statusClass = 'badge bg-success'; break;
                                                    case 'partial_paid': $statusClass = 'badge bg-warning'; break;
                                                    case 'pending': $statusClass = 'badge bg-secondary'; break;
                                                    case 'overdue': $statusClass = 'badge bg-danger'; break;
                                                    default: $statusClass = 'badge bg-secondary';
                                                }
                                            @endphp
                                            <span class="{{ $statusClass }} fs-6">
                                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Notes -->
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Terms & Notes</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Terms:</strong></p>
                                <p class="text-muted small">Payment is due within {{ $invoice->due_date->diffInDays($invoice->invoice_date) }} days from invoice date.</p>
                                
                                @if($invoice->is_performa_invoice)
                                    <p class="mb-0 text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        This is a proforma invoice - not a commercial invoice.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-center text-muted">
                            <p class="mb-0">This is a system generated invoice.</p>
                            <p class="mb-0">Thank you for your business!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal (for quick payment update) -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Update Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $invoice->id }}">
                    
                    <div class="mb-3">
                        <label for="current_paid_amount" class="form-label">Current Paid Amount</label>
                        <input type="text" class="form-control" id="current_paid_amount" 
                               value="₹{{ number_format($invoice->paid_amount ?? 0, 2) }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="grand_total" class="form-label">Grand Total</label>
                        <input type="text" class="form-control" id="grand_total_display" 
                               value="₹{{ number_format($invoice->grand_total, 2) }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="remaining_amount" class="form-label">Remaining Amount</label>
                        <input type="text" class="form-control" id="remaining_amount_display" 
                               value="₹{{ number_format($invoice->grand_total - ($invoice->paid_amount ?? 0), 2) }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount_to_pay" class="form-label">Amount to Pay</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" id="amount_to_pay" name="amount_to_pay" 
                                   step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_total_paid" class="form-label">New Total Paid</label>
                        <input type="text" class="form-control" id="new_total_paid" 
                               value="₹{{ number_format($invoice->paid_amount ?? 0, 2) }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="emailModalLabel">
                    <i class="fas fa-envelope me-2"></i>Send Invoice via Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailInvoiceForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="email_invoice_id" name="invoice_id" value="{{ $invoice->id }}">
                    
                    <div class="mb-3">
                        <label for="recipient_email" class="form-label">
                            <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email" 
                               value="{{ $invoice->getClient->email ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cc_email" class="form-label">
                            <i class="fas fa-copy me-1"></i>CC Email (Optional)
                        </label>
                        <input type="email" class="form-control" id="cc_email" name="cc_email">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email_message" class="form-label">
                            <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                        </label>
                        <textarea class="form-control" id="email_message" name="email_message" rows="3" 
                                  placeholder="Add a personal message to include in the email..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Print Invoice
    $('.print-invoice-btn').on('click', function() {
        var printContents = document.getElementById('invoice-print-area').innerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore original content
    });


    $(document).on('click', '.payment-btn', function() {
        var invoiceId = $(this).data('invoice-id');
        var currentPaid = parseFloat($(this).data('paid-amount')) || 0;
        var grandTotal = parseFloat($(this).data('grand-total')) || 0;
        var remaining = grandTotal - currentPaid;
        
        // Set modal values
        $('#invoice_id').val(invoiceId);
        $('#current_paid_amount').val('₹' + currentPaid.toFixed(2));
        $('#grand_total_display').val('₹' + grandTotal.toFixed(2));
        $('#remaining_amount_display').val('₹' + remaining.toFixed(2));
        
        // Reset amount to pay
        $('#amount_to_pay').val('');
        $('#new_total_paid').val('₹' + currentPaid.toFixed(2));
        
        // Set default payment date to today
        var today = new Date().toISOString().split('T')[0];
        $('#payment_date').val(today);
        
        // Clear notes
        $('#notes').val('');
        
        // Show modal
        var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
    });

    // Calculate new total when amount to pay changes
    $('#amount_to_pay').on('input', function() {
        var currentPaid = parseFloat($('#current_paid_amount').val().replace('₹', '').replace(/,/g, '')) || 0;
        var amountToPay = parseFloat($(this).val()) || 0;
        var newTotal = currentPaid + amountToPay;
        var grandTotal = parseFloat($('#grand_total_display').val().replace('₹', '').replace(/,/g, '')) || 0;
        
        // Validate that new total doesn't exceed grand total
        if (newTotal > grandTotal) {
            $(this).val((grandTotal - currentPaid).toFixed(2));
            newTotal = grandTotal;
            Swal.fire({
                icon: 'warning',
                title: 'Payment Exceeds Grand Total',
                text: 'Payment amount cannot exceed the grand total!',
                timer: 2000,
                showConfirmButton: false
            });
        }
        
        $('#new_total_paid').val('₹' + newTotal.toFixed(2));
    });

    // Handle payment form submission
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = "{{ route('admin.update-invoice-payment') }}";
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
        submitBtn.prop('disabled', true);
        
        // Disable cancel button
        var cancelBtn = $(this).find('button[data-bs-dismiss="modal"]');
        cancelBtn.prop('disabled', true);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
                cancelBtn.prop('disabled', false);
                
                if (response.status == 1) {
                    // Close modal
                    var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    paymentModal.hide();
                    
                    // Show success toast
                    $('#success .toast-body').text(response.message);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();
                    
                    // Reload page after 1.5 seconds to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    $('#danger .toast-body').text(response.message);
                    var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                    dangerToast.show();
                }
            },
            error: function(xhr) {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
                cancelBtn.prop('disabled', false);
                
                var errorMessage = 'An error occurred. Please try again.';
                
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = [];
                    $.each(errors, function(field, messages) {
                        errorMessages.push(messages.join(', '));
                    });
                    errorMessage = errorMessages.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                $('#danger .toast-body').text(errorMessage);
                var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                dangerToast.show();
            }
        });
    });

    // Reset form when modal is hidden
    $('#paymentModal').on('hidden.bs.modal', function () {
        $('#paymentForm')[0].reset();
    });

    // Email Invoice
    $('.email-invoice-btn').on('click', function() {
        var emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
        emailModal.show();
    });

    $('#emailInvoiceForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = "{{ route('admin.email-invoice') }}";
        
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Sending...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
                
                if (response.status == 1) {
                    $('#emailModal').modal('hide');
                    $('#success .toast-body').text(response.message);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();
                    $('#emailInvoiceForm')[0].reset();
                } else {
                    $('#danger .toast-body').text(response.message);
                    var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                    dangerToast.show();
                }
            },
            error: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
                
                $('#danger .toast-body').text('An error occurred. Please try again.');
                var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                dangerToast.show();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-print-area, #invoice-print-area * {
        visibility: visible;
    }
    #invoice-print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn, .page-title-box, .card-header .btn, footer, nav, .breadcrumb {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    .table {
        border-collapse: collapse !important;
    }
    .table td, .table th {
        background-color: #fff !important;
    }
}
</style>
@endpush