@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Create Quotation')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Create Quotation</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.all-quotations') }}">Quotations</a></li>
                        <li class="breadcrumb-item active">Create Quotation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.store-quotation') }}" method="POST" id="quotationForm">
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="quotation_number">Quotation Number</label>
                                    <input type="text" class="form-control" name="quotation_number" id="quotation_number" value="{{ $quotationNumber }}" readonly>
                                    @error('quotation_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 
                            <!-- Client Selection -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="client_id">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-select" required>
                                        <option value="">Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text client_id_error"></span>
                                </div>
                            </div> 
                            
                            <!-- Attention -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="attention">Attention</label>
                                    <input type="text" class="form-control" name="attention" id="attention" 
                                           placeholder="Enter attention/person name">
                                    <span class="text-danger error-text attention_error"></span>
                                </div>
                            </div>
                            
                            <!-- Quotation For -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="quotation_for">Quotation For</label>
                                    <input type="text" class="form-control" name="quotation_for" id="quotation_for" 
                                           placeholder="Enter quotation purpose">
                                    <span class="text-danger error-text quotation_for_error"></span>
                                </div>
                            </div>
                            
                            <!-- Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" id="date" 
                                           value="{{ date('Y-m-d') }}" required>
                                    <span class="text-danger error-text date_error"></span>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="notes">Notes (Optional)</label>
                                    <textarea class="form-control" name="notes" id="notes" rows="3" 
                                            placeholder="Enter any additional notes or remarks...">{{ old('notes') }}</textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Add any special instructions, terms, or remarks for this quotation
                                    </small>
                                    <span class="text-danger error-text notes_error"></span>
                                </div>
                            </div>
                            
                            <!-- Inclusions Section -->
                            <div class="col-md-12">
                                <div class="card border mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Inclusions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_tax_included" name="is_tax_included" value="1">
                                                    <label class="form-check-label" for="is_tax_included">
                                                        Tax Included
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_delivery_charges_included" name="is_delivery_charges_included" value="1">
                                                    <label class="form-check-label" for="is_delivery_charges_included">
                                                        Delivery Charges
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_printing_included" name="is_printing_included" value="1">
                                                    <label class="form-check-label" for="is_printing_included">
                                                        Printing
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_plate_and_punch" name="is_plate_and_punch" value="1">
                                                    <label class="form-check-label" for="is_plate_and_punch">
                                                        Plate & Punch
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_lamination" name="is_lamination" value="1">
                                                    <label class="form-check-label" for="is_lamination">
                                                        Lamination
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Quotation Items -->
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Quotation Items</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0" id="quotation_items_table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50%">Particular <span class="text-danger">*</span></th>
                                                    <th width="20%">GSM</th>
                                                    <th width="20%">Base Price (₹) <span class="text-danger">*</span></th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="quotation_items_body">
                                                <tr>
                                                    <td>
                                                        <input type="text" name="particular[]" class="form-control form-control-sm" 
                                                               placeholder="Item description" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="gsm[]" class="form-control form-control-sm" 
                                                               placeholder="GSM">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="base_price[]" class="form-control form-control-sm base-price" 
                                                               min="0" step="0.01" placeholder="0.00" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="mt-3 text-center">
                                            <button type="button" id="add_item" class="btn btn-outline-primary">
                                                <i class="fas fa-plus me-2"></i>Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 mt-3">
            <button type="submit" class="btn btn-primary w-md">
                <i class="fas fa-save me-2"></i>Create Quotation
            </button>
            <a href="{{ route('admin.all-quotations') }}" class="btn btn-secondary w-md">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        let quotationItemsBody = document.getElementById('quotation_items_body');
        let addItemButton = document.getElementById('add_item');
        
        // Add new item row
        addItemButton.addEventListener('click', function () {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" name="particular[]" class="form-control form-control-sm" 
                           placeholder="Item description" required>
                </td>
                <td>
                    <input type="text" name="gsm[]" class="form-control form-control-sm" 
                           placeholder="GSM">
                </td>
                <td>
                    <input type="number" name="base_price[]" class="form-control form-control-sm base-price" 
                           min="0" step="0.01" placeholder="0.00" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            quotationItemsBody.appendChild(newRow);
        });
        
        // Remove item row
        quotationItemsBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item') || event.target.closest('.remove-item')) {
                event.target.closest('tr').remove();
            }
        });
        
        // Form submission with AJAX
        $("#quotationForm").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);

            // Reset error messages
            $(form).find('span.error-text').text('');

            // Validate at least one item exists
            let itemCount = document.querySelectorAll('#quotation_items_body tr').length;
            if (itemCount === 0) {
                alert('Please add at least one item');
                return false;
            }

            // Show loading state
            var submitBtn = $(form).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    submitBtn.html(originalText).prop('disabled', false);
                    
                    if (response.status == 1) {
                        // Show success toast
                        $('#success .toast-body').text(response.msg);
                        var successToast = new bootstrap.Toast(document.getElementById('success'));
                        successToast.show();
                        
                        // Redirect after delay
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        // Show error toast
                        $('#danger .toast-body').text(response.msg);
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                },
                error: function(xhr) {
                    submitBtn.html(originalText).prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        var errors = xhr.responseJSON.error;
                        $.each(errors, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                        
                        $('#danger .toast-body').text('Please fix the validation errors below.');
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    } else {
                        $('#danger .toast-body').text('An error occurred. Please try again.');
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                }
            });
        });
    });
</script>
@endpush