@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Delivery Challans')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Delivery Challans</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Delivery Challans</li>
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
                    <a href="{{ route('admin.add-delivery-challan') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-plus me-2"></i>Create Delivery Challan
                    </a>
                </div>
                <div class="card-body">
                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                                    aria-describedby="datatable_info">
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
                                                aria-label="Challan No: activate to sort column ascending">Challan No</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Vehicle No: activate to sort column ascending">Vehicle No</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Partner No: activate to sort column ascending">Partner No</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Challan Date: activate to sort column ascending">Challan Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Total Amount: activate to sort column ascending">Total Amount</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="Action: activate to sort column ascending">Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($challans as $challan)
                                            <tr class="odd" data-challan-id="{{ $challan->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="dtr-control">
                                                    {{ $challan->client->name ?? $challan->getClient->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $challan->challan_number }}</td>
                                                <td>{{ $challan->vehicle_no ?? 'N/A' }}</td>
                                                <td>{{ $challan->delivery_partner_phone ?? 'N/A' }}</td>
                                                <td>{{ $challan->challan_date->format('d-m-Y') }}</td>
                                                <td>₹{{ number_format($challan->total_amount, 2) }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.edit-delivery-challan', $challan->id) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Challan">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>

                                                        {{-- ⭐ NEW: Email Delivery Challan Button --}}
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-info email-challan-btn"
                                                                data-challan-id="{{ $challan->id }}"
                                                                data-client-name="{{ $challan->client->name ?? $challan->getClient->name ?? 'N/A' }}"
                                                                data-client-email="{{ $challan->client->email ?? $challan->getClient->email ?? '' }}"
                                                                data-challan-number="{{ $challan->challan_number }}"
                                                                data-total-amount="{{ $challan->total_amount }}"
                                                                data-challan-date="{{ $challan->challan_date->format('d-m-Y') }}"
                                                                data-vehicle-number="{{ $challan->vehicle_no ?? 'N/A' }}"
                                                                data-bs-toggle="tooltip" 
                                                                title="Email Challan">
                                                            <i class="mdi mdi-email-outline"></i>
                                                        </button>

                                                        <a href="{{ route('admin.download-delivery-challan', $challan->id) }}"
                                                           class="btn btn-sm btn-outline-success"
                                                           data-bs-toggle="tooltip"
                                                           title="Download PDF">
                                                            <i class="mdi mdi-download"></i>
                                                        </a>

                                                        <a href="{{ route('admin.delivery-challan-view', $challan->id) }}" 
                                                           class="btn btn-sm btn-outline-warning"
                                                           data-bs-toggle="tooltip" 
                                                           title="View Challan">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>

                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger delete-challan-btn"
                                                                data-challan-id="{{ $challan->id }}"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Challan">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    No delivery challans found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ⭐ NEW: Email Delivery Challan Modal --}}
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
                        <input type="hidden" id="email_challan_id" name="challan_id">
                        
                        <!-- Challan Information Display -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-truck me-1"></i>Delivery Challan Details
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Challan Number:</small>
                                    <p class="mb-1"><strong id="email_challan_number"></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Client:</small>
                                    <p class="mb-1"><strong id="email_client_name"></strong></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Amount:</small>
                                    <p class="mb-0"><strong id="email_total_amount"></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Date:</small>
                                    <p class="mb-0"><strong id="email_challan_date"></strong></p>
                                </div>
                            </div>
                            <div class="row mt-2" id="vehicle_number_row" style="display: none;">
                                <div class="col-12">
                                    <small class="text-muted">Vehicle Number:</small>
                                    <p class="mb-0"><strong id="email_vehicle_number"></strong></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Address Field -->
                        <div class="mb-3">
                            <label for="recipient_email_challan" class="form-label">
                                <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="recipient_email_challan" name="recipient_email" 
                                   placeholder="Enter email address" required>
                            <small class="text-muted">
                                The delivery challan will be sent to this email address. You can change the default client email if needed.
                            </small>
                        </div>
                        
                        <!-- CC Email (Optional) -->
                        <div class="mb-3">
                            <label for="cc_email_challan" class="form-label">
                                <i class="fas fa-copy me-1"></i>CC Email (Optional)
                            </label>
                            <input type="email" class="form-control" id="cc_email_challan" name="cc_email" 
                                   placeholder="Enter CC email address (optional)">
                            <small class="text-muted">Send a copy to another email address.</small>
                        </div>
                        
                        <!-- Custom Message -->
                        <div class="mb-3">
                            <label for="email_message_challan" class="form-label">
                                <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                            </label>
                            <textarea class="form-control" id="email_message_challan" name="email_message" rows="3" 
                                      placeholder="Add a personal message to include in the email..."></textarea>
                            <small class="text-muted">This message will be displayed in the email body.</small>
                        </div>
                        
                        <!-- Email Preview Info -->
                        <div class="alert alert-secondary mb-0">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i>What will be sent:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Professional email template with challan details</li>
                                <li>Delivery Challan PDF as attachment</li>
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
        
        // Handle delete delivery challan
        $(document).on('click', '.delete-challan-btn', function(e) {
            e.preventDefault();
            var challanId = $(this).data('challan-id');
            var url = "{{ route('admin.delete-delivery-challan', ':id') }}".replace(':id', challanId);
            var token = "{{ csrf_token() }}";

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this delivery challan!",
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
                        method: 'DELETE',
                        data: {
                            _token: token
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
                                $('#success .toast-body').text(response.message);
                                var successToast = new bootstrap.Toast(document.getElementById('success'));
                                successToast.show();
                                
                                // Remove row from table
                                $(`[data-challan-id="${challanId}"]`).closest('tr').fadeOut(300, function() {
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

        // ⭐ NEW: Email Delivery Challan Functionality
        
        // Handle email delivery challan button click
        $(document).on('click', '.email-challan-btn', function() {
            var challanId = $(this).data('challan-id');
            var clientName = $(this).data('client-name');
            var clientEmail = $(this).data('client-email');
            var challanNumber = $(this).data('challan-number');
            var totalAmount = $(this).data('total-amount');
            var challanDate = $(this).data('challan-date');
            var vehicleNumber = $(this).data('vehicle-number');
            
            // Set modal values
            $('#email_challan_id').val(challanId);
            $('#email_challan_number').text(challanNumber);
            $('#email_client_name').text(clientName);
            $('#email_total_amount').text('₹' + parseFloat(totalAmount).toFixed(2));
            $('#email_challan_date').text(challanDate);
            
            // Show vehicle number if exists
            if (vehicleNumber && vehicleNumber !== 'N/A') {
                $('#email_vehicle_number').text(vehicleNumber);
                $('#vehicle_number_row').show();
            } else {
                $('#vehicle_number_row').hide();
            }
            
            // Pre-fill recipient email with client's email
            $('#recipient_email_challan').val(clientEmail);
            
            // Clear CC and message fields
            $('#cc_email_challan').val('');
            $('#email_message_challan').val('');
            
            // Show modal
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