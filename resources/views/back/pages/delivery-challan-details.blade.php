@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Delivery Challan Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                Delivery Challan #{{ $challan->challan_number }}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.all-challans') }}">Delivery Challans</a></li>
                    <li class="breadcrumb-item active">Challan Details</li>
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
                    <a href="{{ route('admin.all-challans') }}" class="btn btn-secondary waves-effect waves-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Challans
                    </a>
                    <a href="{{ route('admin.edit-delivery-challan', $challan->id) }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-edit me-2"></i>Edit Challan
                    </a>
                    
                    <button type="button" 
                            class="btn btn-info waves-effect waves-light email-challan-btn"
                            data-challan-id="{{ $challan->id }}"
                            data-client-name="{{ $challan->client->name ?? $challan->getClient->name ?? 'N/A' }}"
                            data-client-email="{{ $challan->client->email ?? $challan->getClient->email ?? '' }}"
                            data-challan-number="{{ $challan->challan_number }}"
                            data-total-amount="{{ $challan->total_amount }}"
                            data-challan-date="{{ $challan->challan_date->format('d-m-Y') }}"
                            data-vehicle-number="{{ $challan->vehicle_no ?? 'N/A' }}">
                        <i class="fas fa-envelope me-2"></i>Email Challan
                    </button>
                    
                    <a href="{{ route('admin.download-delivery-challan', $challan->id) }}" class="btn btn-success waves-effect waves-light">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    <button type="button" 
                            class="btn btn-warning waves-effect waves-light print-challan-btn">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Delivery Challan Details Card -->
        <div class="card" id="challan-print-area">
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
                                <span class="badge bg-primary">DELIVERY CHALLAN</span>
                            </h4>
                            <h5 class="font-size-16 mt-2">Challan #{{ $challan->challan_number }}</h5>
                            <p class="text-muted mb-0">Date: {{ $challan->challan_date->format('d M, Y') }}</p>
                            @if($challan->vehicle_no)
                                <p class="text-muted mb-0">Vehicle: {{ $challan->vehicle_no }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Client and Delivery Details -->
                <div class="row">
                    <!-- Client Details -->
                    <div class="col-sm-6">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Client Details</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="font-size-16 mb-2">{{ $challan->client->name ?? $challan->getClient->name ?? 'N/A' }}</h5>
                                @if($challan->client->address ?? $challan->getClient->address ?? false)
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                        {{ $challan->client->address ?? $challan->getClient->address ?? '' }}
                                    </p>
                                @endif
                                @if($challan->client->email ?? $challan->getClient->email ?? false)
                                    <p class="mb-1">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        {{ $challan->client->email ?? $challan->getClient->email ?? '' }}
                                    </p>
                                @endif
                                @if($challan->client->phone ?? $challan->getClient->phone ?? false)
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $challan->client->phone ?? $challan->getClient->phone ?? '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    <div class="col-sm-6">
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Delivery Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>Delivery Address:</strong>
                                        <p class="mb-1 text-muted">
                                            {{ $challan->consignee_address ?? ($challan->client->address ?? $challan->getClient->address ?? 'No address provided') }}
                                        </p>
                                    </div>
                                    
                                    @if($challan->vehicle_no)
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Vehicle No:</strong><br>
                                            <span class="text-muted">{{ $challan->vehicle_no }}</span>
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($challan->delivery_partner_phone)
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Partner Phone:</strong><br>
                                            <span class="text-muted">{{ $challan->delivery_partner_phone }}</span>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Challan Items Table -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Challan Items</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="55%">Particular</th>
                                                <th width="20%">Quantity</th>
                                                <th width="20%" class="text-end">Total Amount (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($challanItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->particular }}</td>
                                                <td>{{ number_format($item->quantity, 2) }}</td>
                                                <td class="text-end">₹{{ number_format($item->total_amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Amount Summary -->
                <div class="row mt-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Summary</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td width="60%"><strong>Total Items:</strong></td>
                                            <td class="text-end">{{ count($challanItems) }}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td><strong>Grand Total:</strong></td>
                                            <td class="text-end">
                                                <strong>₹{{ number_format($challan->total_amount, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-center text-muted">
                            <p class="mb-0">This is a system generated delivery challan.</p>
                            <p class="mb-0">Thank you for your business!</p>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="row mt-5">
                    <div class="col-6">
                        <div class="text-center border-top pt-2" style="width: 200px;">
                            <p class="mb-0">Receiver's Signature</p>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="text-center border-top pt-2" style="width: 200px; float: right;">
                            <p class="mb-0">Authorized Signature</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal (Reuse the one from index page) -->
<div class="modal fade" id="emailChallanModal" tabindex="-1" aria-labelledby="emailChallanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="emailChallanModalLabel">
                    <i class="fas fa-envelope me-2"></i>Send Delivery Challan via Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailChallanForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="email_challan_id" name="challan_id" value="{{ $challan->id }}">
                    
                    <!-- Challan Information Display -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-truck me-1"></i>Delivery Challan Details
                        </h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Challan Number:</small>
                                <p class="mb-1"><strong>{{ $challan->challan_number }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Client:</small>
                                <p class="mb-1"><strong>{{ $challan->client->name ?? $challan->getClient->name ?? 'N/A' }}</strong></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="text-muted">Amount:</small>
                                <p class="mb-0"><strong>₹{{ number_format($challan->total_amount, 2) }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Date:</small>
                                <p class="mb-0"><strong>{{ $challan->challan_date->format('d-m-Y') }}</strong></p>
                            </div>
                        </div>
                        @if($challan->vehicle_no)
                        <div class="row mt-2">
                            <div class="col-12">
                                <small class="text-muted">Vehicle Number:</small>
                                <p class="mb-0"><strong>{{ $challan->vehicle_no }}</strong></p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Email Address Field -->
                    <div class="mb-3">
                        <label for="recipient_email_challan" class="form-label">
                            <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="recipient_email_challan" name="recipient_email" 
                               value="{{ $challan->client->email ?? $challan->getClient->email ?? '' }}" required>
                    </div>
                    
                    <!-- CC Email (Optional) -->
                    <div class="mb-3">
                        <label for="cc_email_challan" class="form-label">
                            <i class="fas fa-copy me-1"></i>CC Email (Optional)
                        </label>
                        <input type="email" class="form-control" id="cc_email_challan" name="cc_email" 
                               placeholder="Enter CC email address (optional)">
                    </div>
                    
                    <!-- Custom Message -->
                    <div class="mb-3">
                        <label for="email_message_challan" class="form-label">
                            <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                        </label>
                        <textarea class="form-control" id="email_message_challan" name="email_message" rows="3" 
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

    // Print Challan
    $('.print-challan-btn').on('click', function() {
        var printContents = document.getElementById('challan-print-area').innerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore original content
    });

    // Email Challan Button Click
    $(document).on('click', '.email-challan-btn', function() {
        var challanId = $(this).data('challan-id');
        var clientName = $(this).data('client-name');
        var clientEmail = $(this).data('client-email');
        var challanNumber = $(this).data('challan-number');
        var totalAmount = $(this).data('total-amount');
        var challanDate = $(this).data('challan-date');
        var vehicleNumber = $(this).data('vehicle-number');
        
        // Update modal values if needed (already set in the modal)
        var emailChallanModal = new bootstrap.Modal(document.getElementById('emailChallanModal'));
        emailChallanModal.show();
    });

    // Handle email form submission
    $('#emailChallanForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = "{{ route('admin.email-delivery-challan') }}";
        
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
                    var emailChallanModal = bootstrap.Modal.getInstance(document.getElementById('emailChallanModal'));
                    emailChallanModal.hide();
                    
                    // Show success toast
                    $('#success .toast-body').text(response.message);
                    var successToast = new bootstrap.Toast(document.getElementById('success'));
                    successToast.show();
                    
                    // Reset form
                    $('#emailChallanForm')[0].reset();
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
    $('#emailChallanModal').on('hidden.bs.modal', function () {
        $('#emailChallanForm')[0].reset();
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
    #challan-print-area, #challan-print-area * {
        visibility: visible;
    }
    #challan-print-area {
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