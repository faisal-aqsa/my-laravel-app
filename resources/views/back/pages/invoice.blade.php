@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Invoice Page</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Invoice Page</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Success Toast -->
    <div id="success" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
    
    <!-- Error Toast -->
    <div id="danger" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-form-alerts></x-form-alerts>
                <div class="card-header">
                    <a href="{{ route('admin.add-invoice') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-plus me-2"></i>Create Invoice
                    </a>
                </div>
                <div class="card-body">

                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                                    aria-describedby="datatable_info" style="width: 1169px;">
                                    <thead>
                                        <tr>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 50px;"
                                                aria-sort="ascending"
                                                aria-label="SR No: activate to sort column descending">Sr No</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 150px;"
                                                aria-sort="ascending"
                                                aria-label="Client Name: activate to sort column descending">Client Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Invoice No: activate to sort column ascending">Invoice Number</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Sub Total: activate to sort column ascending">Sub Total</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Paid Amount: activate to sort column ascending">Paid Amount</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Remaining: activate to sort column ascending">Remaining</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Grand Total: activate to sort column ascending">Grand Total</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Status: activate to sort column ascending">Status</th>
                                            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Invoice Date: activate to sort column ascending">Invoice Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Due Date: activate to sort column ascending">Due Date</th> -->
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="Action: activate to sort column ascending">Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($invoices as $key => $invoice)
                                            @php
                                                $remaining = $invoice->grand_total - $invoice->paid_amount;
                                                $statusClass = '';
                                                switch($invoice->status) {
                                                    case 'paid': $statusClass = 'badge bg-success'; break;
                                                    case 'partial_paid': $statusClass = 'badge bg-warning'; break;
                                                    case 'pending': $statusClass = 'badge bg-secondary'; break;
                                                    case 'overdue': $statusClass = 'badge bg-danger'; break;
                                                    default: $statusClass = 'badge bg-secondary';
                                                }
                                            @endphp
                                            <tr class="odd" data-invoice-id="{{ $invoice->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="dtr-control sorting_1" tabindex="0">{{ $invoice->getClient->name ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                                                
                                                <!-- Paid Amount Cell (Inline Editable) -->
                                                <td>
                                                    <div class="paid-amount-container" data-invoice-id="{{ $invoice->id }}">
                                                        <span class="paid-amount-display">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                                                        <button class="btn btn-sm btn-outline-primary edit-paid-amount" 
                                                                data-invoice-id="{{ $invoice->id }}"
                                                                data-paid-amount="{{ $invoice->paid_amount }}"
                                                                data-grand-total="{{ $invoice->grand_total }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                
                                                <!-- Remaining Amount Cell -->
                                                <td>
                                                    <span class="remaining-amount-display" data-invoice-id="{{ $invoice->id }}">
                                                        ₹{{ number_format($remaining, 2) }}
                                                    </span>
                                                </td>
                                                
                                                <td>₹{{ number_format($invoice->grand_total, 2) }}</td>
                                                
                                                <!-- Status Cell -->
                                                <td>
                                                    <span class="{{ $statusClass }} status-display" data-invoice-id="{{ $invoice->id }}">
                                                        {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                                    </span>
                                                </td>
                                                
                                                <!-- <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                                <td>{{ $invoice->due_date->format('d-m-Y') }}</td> -->
                                                
                                                {{-- ⭐ NEW: Updated Action Buttons with Email Button --}}
                                                <td>
                                                    <div class="btn-group" role="group">

                                                        <a href="{{ route('admin.invoice-view-details', $invoice->id) }}" 
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="tooltip" title="View Details">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>

                                                        <a href="{{ route('admin.edit-invoice', ['id' => $invoice->id]) }}" 
                                                            class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="Edit Invoice">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>

                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary email-invoice-btn"
                                                            data-invoice-id="{{ $invoice->id }}"
                                                            data-client-name="{{ $invoice->getClient->name ?? 'N/A' }}"
                                                            data-client-email="{{ $invoice->getClient->email ?? '' }}"
                                                            data-invoice-number="{{ $invoice->invoice_number }}"
                                                            data-grand-total="{{ $invoice->grand_total }}"
                                                            data-status="{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}"
                                                            data-bs-toggle="tooltip" title="Email Invoice">
                                                            <i class="mdi mdi-email-outline"></i>
                                                        </button>

                                                        <a href="{{ route('admin.invoice-download', $invoice->id) }}" 
                                                            class="btn btn-sm btn-outline-success"
                                                            data-bs-toggle="tooltip" title="Download Invoice">
                                                            <i class="mdi mdi-download"></i>
                                                        </a>

                                                        <a href="{{ route('admin.invoice-view', $invoice->id) }}" 
                                                            class="btn btn-sm btn-outline-dark"
                                                            data-bs-toggle="tooltip" title="View PDF">
                                                            <i class="mdi mdi-file-pdf-box"></i>
                                                        </a>

                                                        <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-invoice-btn"
                                                            data-invoice-id="{{ $invoice->id }}"
                                                            data-bs-toggle="tooltip" title="Delete Invoice">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">No invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    <!-- Payment Modal -->
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
                        <input type="hidden" id="invoice_id" name="invoice_id">
                        
                        <div class="mb-3">
                            <label for="current_paid_amount" class="form-label">Current Paid Amount</label>
                            <input type="text" class="form-control" id="current_paid_amount" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="grand_total" class="form-label">Grand Total</label>
                            <input type="text" class="form-control" id="grand_total_display" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input type="text" class="form-control" id="remaining_amount_display" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_type" class="form-label">Payment Type</label>
                            <select class="form-select" id="payment_type" name="payment_type">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount_to_pay" class="form-label">Amount to Pay</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="amount_to_pay" name="amount_to_pay" 
                                       step="0.01" min="0" required>
                            </div>
                            <small class="text-muted">Enter amount to add to current payment</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_total_paid" class="form-label">New Total Paid</label>
                            <input type="text" class="form-control" id="new_total_paid" readonly>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Status will be updated as:</h6>
                            <ul class="mb-0">
                                <li><strong>Paid:</strong> When new total equals grand total</li>
                                <li><strong>Partial Paid:</strong> When payment is made but total is less than grand total</li>
                                <li><strong>Pending:</strong> When no payment is made</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Payment Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                      placeholder="Enter any payment notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ⭐ NEW: Email Invoice Modal --}}
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
                        <input type="hidden" id="email_invoice_id" name="invoice_id">
                        
                        <!-- Invoice Information Display -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-file-invoice me-1"></i>Invoice Details
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Invoice Number:</small>
                                    <p class="mb-1"><strong id="email_invoice_number"></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Client:</small>
                                    <p class="mb-1"><strong id="email_client_name"></strong></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Amount:</small>
                                    <p class="mb-0"><strong id="email_grand_total"></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Status:</small>
                                    <p class="mb-0"><strong id="email_status"></strong></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Address Field -->
                        <div class="mb-3">
                            <label for="recipient_email" class="form-label">
                                <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="recipient_email" name="recipient_email" 
                                   placeholder="Enter email address" required>
                            <small class="text-muted">
                                The invoice will be sent to this email address. You can change the default client email if needed.
                            </small>
                        </div>
                        
                        <!-- CC Email (Optional) -->
                        <div class="mb-3">
                            <label for="cc_email" class="form-label">
                                <i class="fas fa-copy me-1"></i>CC Email (Optional)
                            </label>
                            <input type="email" class="form-control" id="cc_email" name="cc_email" 
                                   placeholder="Enter CC email address (optional)">
                            <small class="text-muted">Send a copy to another email address.</small>
                        </div>
                        
                        <!-- Custom Message -->
                        <div class="mb-3">
                            <label for="email_message" class="form-label">
                                <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                            </label>
                            <textarea class="form-control" id="email_message" name="email_message" rows="3" 
                                      placeholder="Add a personal message to include in the email..."></textarea>
                            <small class="text-muted">This message will be displayed in the email body.</small>
                        </div>
                        
                        <!-- Email Preview Info -->
                        <div class="alert alert-secondary mb-0">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i>What will be sent:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Professional email template with invoice details</li>
                                <li>Invoice PDF as attachment</li>
                                <li>Your custom message (if provided)</li>
                            </ul>
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
        
        // ========== EXISTING PAYMENT CODE (Keep as is) ==========
        
        // Show payment modal when edit button is clicked
        $(document).on('click', '.edit-paid-amount', function() {
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
            
            // Show modal
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        });
        
        // Calculate new total when amount to pay changes
        $('#amount_to_pay').on('input', function() {
            var currentPaid = parseFloat($('#current_paid_amount').val().replace('₹', '')) || 0;
            var amountToPay = parseFloat($(this).val()) || 0;
            var newTotal = currentPaid + amountToPay;
            var grandTotal = parseFloat($('#grand_total_display').val().replace('₹', '')) || 0;
            
            // Validate that new total doesn't exceed grand total
            if (newTotal > grandTotal) {
                $(this).val((grandTotal - currentPaid).toFixed(2));
                newTotal = grandTotal;
                alert('Payment cannot exceed the grand total!');
            }
            
            $('#new_total_paid').val('₹' + newTotal.toFixed(2));
        });
        
        // Handle payment form submission
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var invoiceId = $('#invoice_id').val();
            var url = "{{ route('admin.update-invoice-payment') }}";
            
            // Show loading state
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
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
                        // Update the table row
                        updateInvoiceRow(response.invoice_id, response.data);
                        
                        // Close modal
                        var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                        paymentModal.hide();
                        
                        // Show success toast
                        $('#success .toast-body').text(response.message);
                        var successToast = new bootstrap.Toast(document.getElementById('success'));
                        successToast.show();
                    } else {
                        $('#danger .toast-body').text(response.message);
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                },
                error: function(xhr) {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = [];
                        $.each(errors, function(field, messages) {
                            errorMessages.push(messages.join(', '));
                        });
                        $('#danger .toast-body').text(errorMessages.join('\n'));
                    } else {
                        $('#danger .toast-body').text('An error occurred. Please try again.');
                    }
                    
                    var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                    dangerToast.show();
                }
            });
        });
        
        // Function to update invoice row after payment
        function updateInvoiceRow(invoiceId, data) {
            var row = $('tr[data-invoice-id="' + invoiceId + '"]');
            
            // Update paid amount
            row.find('.paid-amount-display').text('₹' + parseFloat(data.paid_amount).toFixed(2));
            row.find('.edit-paid-amount').data('paid-amount', data.paid_amount);
            
            // Calculate and update remaining amount
            var remaining = data.grand_total - data.paid_amount;
            row.find('.remaining-amount-display').text('₹' + remaining.toFixed(2));
            
            // Update status
            var statusText = data.status.replace('_', ' ');
            statusText = statusText.charAt(0).toUpperCase() + statusText.slice(1);
            row.find('.status-display').text(statusText);
            
            // Update status badge class
            var statusDisplay = row.find('.status-display');
            statusDisplay.removeClass('badge-success badge-warning badge-secondary badge-danger');
            
            switch(data.status) {
                case 'paid':
                    statusDisplay.addClass('badge bg-success');
                    break;
                case 'partial_paid':
                    statusDisplay.addClass('badge bg-warning');
                    break;
                case 'pending':
                    statusDisplay.addClass('badge bg-secondary');
                    break;
                case 'overdue':
                    statusDisplay.addClass('badge bg-danger');
                    break;
                default:
                    statusDisplay.addClass('badge bg-secondary');
            }
        }
        
        // Handle delete invoice
        $(document).on('click', '.delete-invoice-btn', function(e) {
            e.preventDefault();
            var invoiceId = $(this).data('invoice-id');
             var url = "{{ route('admin.delete-invoice', ':id') }}".replace(':id', invoiceId);
            var token = "{{ csrf_token() }}";

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this invoice!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2ab57d",
                cancelButtonColor: "#fd625e",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                allowOutsideClick: false,
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: token,
                            invoice_id: invoiceId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
                                $('#success .toast-body').text(response.message);
                                var successToast = new bootstrap.Toast(document.getElementById('success'));
                                successToast.show();
                                
                                // Remove row from table
                                $('tr[data-invoice-id="' + invoiceId + '"]').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            } else {
                                $('#danger .toast-body').text(response.message);
                                var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                                dangerToast.show();
                            }
                        },
                        error: function() {
                            $('#danger .toast-body').text('An error occurred. Please try again.');
                            var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                            dangerToast.show();
                        }
                    });
                }
            });
        });
        
        // Check for overdue invoices on page load
        function checkOverdueInvoices() {
            var today = new Date().toISOString().split('T')[0];
            $('tr[data-invoice-id]').each(function() {
                var dueDate = $(this).find('td:eq(9)').text().split('-').reverse().join('-');
                var status = $(this).find('.status-display').text().toLowerCase().replace(' ', '_');
                
                // Compare dates
                if (status === 'pending' && dueDate < today) {
                    var invoiceId = $(this).data('invoice-id');
                    updateInvoiceStatus(invoiceId, 'overdue');
                }
            });
        }
        
        // Function to update invoice status
        function updateInvoiceStatus(invoiceId, newStatus) {
            $.ajax({
                url: "{{ route('admin.update-invoice-status') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    invoice_id: invoiceId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        updateInvoiceRow(invoiceId, response.data);
                    }
                }
            });
        }
        
        // Run overdue check on page load
        checkOverdueInvoices();
        
        // ⭐ NEW: Email Invoice Functionality
        
        // Handle email invoice button click
        $(document).on('click', '.email-invoice-btn', function() {
            var invoiceId = $(this).data('invoice-id');
            var clientName = $(this).data('client-name');
            var clientEmail = $(this).data('client-email');
            var invoiceNumber = $(this).data('invoice-number');
            var grandTotal = $(this).data('grand-total');
            var status = $(this).data('status');
            
            // Set modal values
            $('#email_invoice_id').val(invoiceId);
            $('#email_invoice_number').text(invoiceNumber);
            $('#email_client_name').text(clientName);
            $('#email_grand_total').text('₹' + parseFloat(grandTotal).toFixed(2));
            $('#email_status').text(status.toUpperCase());
            
            // Pre-fill recipient email with client's email
            $('#recipient_email').val(clientEmail);
            
            // Clear CC and message fields
            $('#cc_email').val('');
            $('#email_message').val('');
            
            // Show modal
            var emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
            emailModal.show();
        });
        
        // Handle email form submission
        $('#emailInvoiceForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var url = "{{ route('admin.email-invoice') }}";
            
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
                        var emailModal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
                        emailModal.hide();
                        
                        // Show success toast
                        $('#success .toast-body').text(response.message);
                        var successToast = new bootstrap.Toast(document.getElementById('success'));
                        successToast.show();
                        
                        // Reset form
                        $('#emailInvoiceForm')[0].reset();
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
        $('#emailModal').on('hidden.bs.modal', function () {
            $('#emailInvoiceForm')[0].reset();
        });
    });
</script>
@endpush