@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Quotations')

@push('custom-styles')
    <style>

    .client-name-cell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #datatable th:nth-child(2),
    #datatable td:nth-child(2) {
        width: 60px;
        min-width: 60px;
        text-align: center;
    }

    @media (max-width: 576px) {

        .client-name-cell {
            white-space: normal !important;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        #datatable th:nth-child(2),
        #datatable td:nth-child(2) {
            width: 50px;
            min-width: 50px;
        }
    }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quotations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Quotations</li>
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
                    <a href="{{ route('admin.add-quotation') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-plus me-2"></i>Create Quotation
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
                                            <th tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 50px;"
                                                aria-sort="ascending"
                                                aria-label="Client Name: activate to sort column descending">Client Name</th>
                                            <th tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 50px;"
                                                aria-sort="ascending"
                                                aria-label="Quotation Number: activate to sort column descending">Quotation Number</th>
                                            <th tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Attention: activate to sort column ascending">Attention</th>
                                            <th tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Quotation For: activate to sort column ascending">Quotation For</th>
                                            <th tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Quotation Date: activate to sort column ascending">Quotation Date</th>
                                            <th tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="Action: activate to sort column ascending">Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($quotations as $quotation)
                                            <tr class="odd" data-quotation-id="{{ $quotation->id }}">
                                                <td class="dtr-control client-name-cell">{{ $quotation->client->name ?? 'N/A' }}</td>
                                                <td>{{ $quotation->quotation_number }}</td>
                                                <td>{{ $quotation->attention }}</td>
                                                <td>{{ $quotation->quotation_for ?? 'N/A' }}</td>
                                                <td>{{ $quotation->date->format('d-m-Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.quotation-view-details', $quotation->id) }}" 
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="tooltip" title="View Details">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                        {{-- Edit --}}
                                                        <a href="{{ route('admin.edit-quotation', $quotation->id) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Quotation">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>

                                                        {{-- Email --}}
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-secondary email-quotation-btn"
                                                                data-quotation-id="{{ $quotation->id }}"
                                                                data-client-name="{{ $quotation->client->name ?? 'N/A' }}"
                                                                data-client-email="{{ $quotation->client->email ?? '' }}"
                                                                data-quotation-date="{{ $quotation->date->format('d-m-Y') }}"
                                                                data-attention="{{ $quotation->attention ?? '' }}"
                                                                data-quotation-for="{{ $quotation->quotation_for ?? '' }}"
                                                                data-bs-toggle="tooltip"
                                                                title="Email Quotation">
                                                            <i class="mdi mdi-email-outline"></i>
                                                        </button>

                                                        {{-- Download --}}
                                                        <a href="{{ route('admin.download-quotation', $quotation->id) }}"
                                                           class="btn btn-sm btn-outline-success"
                                                           data-bs-toggle="tooltip"
                                                           title="Download PDF">
                                                            <i class="mdi mdi-download"></i>
                                                        </a>

                                                        {{-- View --}}
                                                        <a href="{{ route('admin.quotation-view', $quotation->id) }}"
                                                           class="btn btn-sm btn-outline-dark"
                                                           data-bs-toggle="tooltip"
                                                           title="View Quotation">
                                                            <i class="mdi mdi-file-pdf-box"></i>
                                                        </a>

                                                        {{-- Delete --}}
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger delete-quotation-btn"
                                                                data-quotation-id="{{ $quotation->id }}"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Quotation">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No quotations found</td>
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

    {{-- Email Quotation Modal --}}
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
                        <input type="hidden" id="email_quotation_id" name="quotation_id">

                        {{-- Quotation Info Summary --}}
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-file-alt me-1"></i>Quotation Details
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Client:</small>
                                    <p class="mb-1"><strong id="eq_client_name"></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Date:</small>
                                    <p class="mb-1"><strong id="eq_quotation_date"></strong></p>
                                </div>
                            </div>
                            <div class="row mt-1" id="eq_attention_row">
                                <div class="col-6">
                                    <small class="text-muted">Attention:</small>
                                    <p class="mb-1"><strong id="eq_attention"></strong></p>
                                </div>
                                <div class="col-6" id="eq_quotation_for_col">
                                    <small class="text-muted">Quotation For:</small>
                                    <p class="mb-1"><strong id="eq_quotation_for"></strong></p>
                                </div>
                            </div>
                        </div>

                        {{-- Recipient Email --}}
                        <div class="mb-3">
                            <label for="eq_recipient_email" class="form-label">
                                <i class="fas fa-at me-1"></i>Recipient Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="eq_recipient_email"
                                   name="recipient_email" placeholder="Enter email address" required>
                            <small class="text-muted">Pre-filled with client email. Change if needed.</small>
                        </div>

                        {{-- CC Email --}}
                        <div class="mb-3">
                            <label for="eq_cc_email" class="form-label">
                                <i class="fas fa-copy me-1"></i>CC Email (Optional)
                            </label>
                            <input type="email" class="form-control" id="eq_cc_email"
                                   name="cc_email" placeholder="Enter CC email address (optional)">
                        </div>

                        {{-- Custom Message --}}
                        <div class="mb-3">
                            <label for="eq_email_message" class="form-label">
                                <i class="fas fa-comment-dots me-1"></i>Custom Message (Optional)
                            </label>
                            <textarea class="form-control" id="eq_email_message" name="email_message"
                                      rows="3" placeholder="Add a personal message..."></textarea>
                        </div>

                        {{-- What will be sent --}}
                        <div class="alert alert-secondary mb-0">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i>What will be sent:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Professional email template with quotation details</li>
                                <li>Quotation PDF as attachment</li>
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
$(document).ready(function () {

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // -------------------------------------------------------
    // DELETE QUOTATION
    // -------------------------------------------------------
    $(document).on('click', '.delete-quotation-btn', function (e) {
        e.preventDefault();
        var quotationId = $(this).data('quotation-id');
        var url   = "{{ route('admin.delete-quotation', ':id') }}".replace(':id', quotationId);
        var token = "{{ csrf_token() }}";

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this quotation!",
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
                    data: { _token: token },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 1) {
                            $('#success .toast-body').text(response.message);
                            new bootstrap.Toast(document.getElementById('success')).show();
                            $('[data-quotation-id="' + quotationId + '"]').closest('tr').fadeOut(300, function () {
                                $(this).remove();
                            });
                        } else {
                            $('#danger .toast-body').text(response.message);
                            new bootstrap.Toast(document.getElementById('danger')).show();
                        }
                    },
                    error: function () {
                        $('#danger .toast-body').text('An error occurred. Please try again.');
                        new bootstrap.Toast(document.getElementById('danger')).show();
                    }
                });
            }
        });
    });

    // -------------------------------------------------------
    // OPEN EMAIL MODAL
    // -------------------------------------------------------
    $(document).on('click', '.email-quotation-btn', function () {
        var quotationId   = $(this).data('quotation-id');
        var clientName    = $(this).data('client-name');
        var clientEmail   = $(this).data('client-email');
        var quotationDate = $(this).data('quotation-date');
        var attention     = $(this).data('attention');
        var quotationFor  = $(this).data('quotation-for');

        // Populate summary
        $('#email_quotation_id').val(quotationId);
        $('#eq_client_name').text(clientName);
        $('#eq_quotation_date').text(quotationDate);
        $('#eq_attention').text(attention || '—');
        $('#eq_quotation_for').text(quotationFor || '—');

        // Pre-fill recipient email
        $('#eq_recipient_email').val(clientEmail);

        // Clear optional fields
        $('#eq_cc_email').val('');
        $('#eq_email_message').val('');

        new bootstrap.Modal(document.getElementById('emailQuotationModal')).show();
    });

    // -------------------------------------------------------
    // SUBMIT EMAIL FORM
    // -------------------------------------------------------
    $('#emailQuotationForm').on('submit', function (e) {
        e.preventDefault();

        var submitBtn   = $(this).find('button[type="submit"]');
        var cancelBtn   = $(this).find('button[data-bs-dismiss="modal"]');
        var originalHtml = submitBtn.html();

        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Sending...').prop('disabled', true);
        cancelBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.email-quotation') }}",
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                submitBtn.html(originalHtml).prop('disabled', false);
                cancelBtn.prop('disabled', false);

                if (response.status == 1) {
                    bootstrap.Modal.getInstance(document.getElementById('emailQuotationModal')).hide();
                    $('#success .toast-body').text(response.message);
                    new bootstrap.Toast(document.getElementById('success')).show();
                    $('#emailQuotationForm')[0].reset();
                } else {
                    $('#danger .toast-body').text(response.message);
                    new bootstrap.Toast(document.getElementById('danger')).show();
                }
            },
            error: function (xhr) {
                submitBtn.html(originalHtml).prop('disabled', false);
                cancelBtn.prop('disabled', false);

                var errorMessage = 'An error occurred. Please try again.';
                if (xhr.status === 422) {
                    var msgs = [];
                    $.each(xhr.responseJSON.errors, function (f, m) { msgs.push(m.join(', ')); });
                    errorMessage = msgs.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#danger .toast-body').text(errorMessage);
                new bootstrap.Toast(document.getElementById('danger')).show();
            }
        });
    });

    // Reset form on modal close
    $('#emailQuotationModal').on('hidden.bs.modal', function () {
        $('#emailQuotationForm')[0].reset();
    });

});
</script>
@endpush