@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Quotation Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                Quotation #{{ $quotation->id }}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.all-quotations') }}">Quotations</a></li>
                    <li class="breadcrumb-item active">Quotation Details</li>
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
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('admin.all-quotations') }}" class="btn btn-secondary waves-effect waves-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Quotations
                    </a>
                    <a href="{{ route('admin.edit-quotation', $quotation->id) }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-edit me-2"></i>Edit Quotation
                    </a>
                    
                    <button type="button" 
                            class="btn btn-info waves-effect waves-light email-quotation-btn"
                            data-quotation-id="{{ $quotation->id }}"
                            data-client-name="{{ $quotation->client->name ?? 'N/A' }}"
                            data-client-email="{{ $quotation->client->email ?? '' }}"
                            data-quotation-date="{{ $quotation->date->format('d-m-Y') }}"
                            data-attention="{{ $quotation->attention ?? '' }}"
                            data-quotation-for="{{ $quotation->quotation_for ?? '' }}">
                        <i class="fas fa-envelope me-2"></i>Email Quotation
                    </button>
                    
                    <a href="{{ route('admin.download-quotation', $quotation->id) }}" class="btn btn-success waves-effect waves-light">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    
                    <button type="button" 
                            class="btn btn-warning waves-effect waves-light print-quotation-btn">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Quotation Details Card -->
        <div class="card" id="quotation-print-area">
            <div class="card-body">
                <!-- Header with Logo and Title -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            @php
                                $settings = \App\Models\Setting::first();
                            @endphp
                            <div>
                                <h4 class="font-size-24 mb-1">BOXMAKER</h4>
                                <p class="text-muted mb-0">{{ $settings->address ?? '' }}</p>
                                <p class="text-muted mb-0">GST: {{ $settings->gst_no ?? 'N/A' }}</p>
                                <p class="text-muted mb-0">Email: {{ $settings->email ?? '' }} | Phone: {{ $settings->phone ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <div>
                            <h4 class="font-size-20">
                                <span class="badge bg-primary">QUOTATION</span>
                            </h4>
                            <h5 class="font-size-16 mt-2">Quotation #{{ $quotation->id }}</h5>
                            <p class="text-muted mb-0">Date: {{ $quotation->date->format('d M, Y') }}</p>
                            <p class="text-muted mb-0">Valid Until: {{ $quotation->date->addDays(30)->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Client Details -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Client Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-size-16 mb-3">{{ $quotation->client->name ?? 'N/A' }}</h5>
                                        
                                        @if($quotation->attention)
                                        <p class="mb-2">
                                            <strong>Attention:</strong> {{ $quotation->attention }}
                                        </p>
                                        @endif
                                        
                                        @if($quotation->quotation_for)
                                        <p class="mb-2">
                                            <strong>Quotation For:</strong> {{ $quotation->quotation_for }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($quotation->client->address ?? false)
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            {{ $quotation->client->address }}
                                        </p>
                                        @endif
                                        
                                        @if($quotation->client->email ?? false)
                                        <p class="mb-2">
                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                            {{ $quotation->client->email }}
                                        </p>
                                        @endif
                                        
                                        @if($quotation->client->phone ?? false)
                                        <p class="mb-0">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            {{ $quotation->client->phone }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inclusions Section -->
                @if($quotation->is_tax_included || $quotation->is_delivery_charges_included || $quotation->is_printing_included || $quotation->is_plate_and_punch || $quotation->is_lamination)
                <div class="row">
                    <div class="col-12">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Inclusions</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($quotation->is_tax_included)
                                    <div class="col-md-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i> Tax Included
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($quotation->is_delivery_charges_included)
                                    <div class="col-md-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i> Delivery Charges
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($quotation->is_printing_included)
                                    <div class="col-md-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i> Printing
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($quotation->is_plate_and_punch)
                                    <div class="col-md-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i> Plate & Punch
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($quotation->is_lamination)
                                    <div class="col-md-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i> Lamination
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes Section (if exists) -->
                @if($quotation->notes)
                    <div class="row">
                        <div class="col-12">
                            <div class="card border shadow-none mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes / Remarks</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $quotation->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quotation Items Table -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Quotation Items</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="55%">Particular</th>
                                                <th width="15%">GSM</th>
                                                <th width="25%" class="text-end">Base Price (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($quotationItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->particular }}</td>
                                                <td>{{ $item->gsm ?? 'N/A' }}</td>
                                                <td class="text-end">₹{{ number_format($item->base_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <!-- <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Terms & Conditions</h6>
                            </div>
                            <div class="card-body">
                                <ol class="mb-0 ps-3">
                                    <li class="mb-1">This quotation is valid for 30 days from the date of issue.</li>
                                    <li class="mb-1">All prices are in Indian Rupees (₹).</li>
                                    <li class="mb-1">Payment terms: 50% advance and 50% before delivery.</li>
                                    <li class="mb-1">Goods once sold will not be taken back.</li>
                                    <li class="mb-1">Delivery will be made within 7-10 working days after confirmation.</li>
                                    <li class="mb-1">Any dispute subject to local jurisdiction.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Footer -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-center text-muted">
                            <p class="mb-0">This is a computer generated quotation, no signature is required.</p>
                            <p class="mb-0">Thank you for your business!</p>
                        </div>
                    </div>
                </div>

                <!-- Authorized Signatory -->
                <div class="row mt-5">
                    <div class="col-12 text-end">
                        <div class="border-top pt-2" style="width: 250px; float: right;">
                            <p class="mb-0">For BOXMAKER</p>
                            <br><br>
                            <p class="mb-0">Authorized Signatory</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailQuotationModal" tabindex="-1" aria-labelledby="emailQuotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="emailQuotationModalLabel">
                    <i class="fas fa-envelope me-2"></i>Send Quotation via Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailQuotationForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="email_quotation_id" name="quotation_id" value="{{ $quotation->id }}">
                    
                    <!-- Quotation Information Display -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-file-invoice me-1"></i>Quotation Details
                        </h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Quotation ID:</small>
                                <p class="mb-1"><strong>#{{ $quotation->id }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Client:</small>
                                <p class="mb-1"><strong>{{ $quotation->client->name ?? 'N/A' }}</strong></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="text-muted">Date:</small>
                                <p class="mb-0"><strong>{{ $quotation->date->format('d-m-Y') }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Grand Total:</small>
                                <p class="mb-0"><strong>₹{{ number_format($quotation->grand_total, 2) }}</strong></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email Address Field -->
                    <div class="mb-3">
                        <label for="recipient_email_quotation" class="form-label">
                            <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="recipient_email_quotation" name="recipient_email" 
                               value="{{ $quotation->client->email ?? '' }}" required>
                    </div>
                    
                    <!-- CC Email (Optional) -->
                    <div class="mb-3">
                        <label for="cc_email_quotation" class="form-label">
                            <i class="fas fa-copy me-1"></i>CC Email (Optional)
                        </label>
                        <input type="email" class="form-control" id="cc_email_quotation" name="cc_email" 
                               placeholder="Enter CC email address (optional)">
                    </div>
                    
                    <!-- Custom Message -->
                    <div class="mb-3">
                        <label for="email_message_quotation" class="form-label">
                            <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                        </label>
                        <textarea class="form-control" id="email_message_quotation" name="email_message" rows="3" 
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

    // Print Quotation
    $('.print-quotation-btn').on('click', function() {
        var printContents = document.getElementById('quotation-print-area').innerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore original content
    });

    // Email Quotation Button Click
    $(document).on('click', '.email-quotation-btn', function() {
        var emailModal = new bootstrap.Modal(document.getElementById('emailQuotationModal'));
        emailModal.show();
    });

    // Handle email form submission
    $('#emailQuotationForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = "{{ route('admin.email-quotation') }}";
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Sending Email...');
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
                    var emailModal = bootstrap.Modal.getInstance(document.getElementById('emailQuotationModal'));
                    emailModal.hide();
                    
                    // Show success toast
                    $('#success .toast-body').text(response.message);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();
                    
                    // Reset form
                    $('#emailQuotationForm')[0].reset();
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
    $('#emailQuotationModal').on('hidden.bs.modal', function () {
        $('#emailQuotationForm')[0].reset();
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
    #quotation-print-area, #quotation-print-area * {
        visibility: visible;
    }
    #quotation-print-area {
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
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background-color: #fff !important;
    }
}
</style>
@endpush