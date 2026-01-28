@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add Settings' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Add Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.store-setting') }}" method="POST" id="addSettingForm" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <div class="row">
                            <!-- Company Details -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_name">Name</label>
                                    <input type="text" class="form-control" id="setting_name" name="setting_name" 
                                           placeholder="Enter Company Name"
                                           value="{{ old('setting_name') }}">
                                    <span class="text-danger error-text setting_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_phone">Phone Number</label>
                                    <input type="text" class="form-control" id="setting_phone" name="setting_phone" 
                                           placeholder="Enter Phone"
                                           value="{{ old('setting_phone') }}">
                                    <span class="text-danger error-text setting_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_email">Email</label>
                                    <input type="email" class="form-control" id="setting_email" name="setting_email" 
                                           placeholder="Enter Email"
                                           value="{{ old('setting_email') }}">
                                    <span class="text-danger error-text setting_email_error"></span>
                                </div>
                            </div>
                            
                            <!-- GST Details -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_gst_no">GST Number</label>
                                    <input type="text" class="form-control" id="setting_gst_no" name="setting_gst_no" 
                                           placeholder="Enter GST Number"
                                           value="{{ old('setting_gst_no') }}">
                                    <span class="text-danger error-text setting_gst_no_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_website_url">Website URL</label>
                                    <input type="text" class="form-control" id="setting_website_url" name="setting_website_url" 
                                           placeholder="Enter Website URL"
                                           value="{{ old('setting_website_url') }}">
                                    <span class="text-danger error-text setting_website_url_error"></span>
                                </div>
                            </div>
                            
                            <!-- Signature -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_signature">Signature</label>
                                    <input type="file" class="form-control" id="setting_signature" name="setting_signature" accept="image/*">
                                    <span class="text-danger error-text setting_signature_error"></span>
                                    <small class="text-muted">Allowed: PNG, JPG, JPEG (Max: 2MB)</small>
                                </div>
                            </div>
                            
                            <!-- Tax Rates -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_sgst">SGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control tax-percentage" id="setting_sgst" name="setting_sgst" 
                                               value="{{ old('setting_sgst', 2.5) }}"
                                               placeholder="Enter SGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_sgst_error"></span>
                                    <small class="text-muted">State Goods and Services Tax</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_cgst">CGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control tax-percentage" id="setting_cgst" name="setting_cgst" 
                                               value="{{ old('setting_cgst', 2.5) }}"
                                               placeholder="Enter CGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_cgst_error"></span>
                                    <small class="text-muted">Central Goods and Services Tax</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_igst">IGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control tax-percentage" id="setting_igst" name="setting_igst" 
                                               value="{{ old('setting_igst', 5) }}"
                                               placeholder="Enter IGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_igst_error"></span>
                                    <small class="text-muted">Integrated Goods and Services Tax</small>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_address">Address</label>
                                    <textarea name="setting_address" id="setting_address" class="form-control" 
                                              placeholder="Enter Address" rows="3">{{ old('setting_address') }}</textarea>
                                    <span class="text-danger error-text setting_address_error"></span>
                                </div>
                            </div>
                            
                            <!-- Tax Summary -->
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Tax Summary</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-primary me-2">SGST</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span id="sgst_display">2.5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-success me-2">CGST</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span id="cgst_display">2.5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-info me-2">IGST</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span id="igst_display">5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="alert alert-info mt-2 mb-0">
                                                    <small>
                                                        <i class="fas fa-info-circle"></i> 
                                                        For intra-state transactions: CGST + SGST = <span id="total_intra_state">5.00</span>%
                                                        <br>
                                                        For inter-state transactions: IGST = <span id="total_inter_state">5.00</span>%
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">
                <i class="fas fa-save"></i> Save Settings
            </button>
            <a href="{{ route('admin.settings') }}" class="btn btn-secondary w-md ms-2">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $("#addSettingForm").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);

            // Reset error messages
            $(form).find('span.error-text').text('');

            // Show loading state
            var submitBtn = $(form).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

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
                        // Success - show success toast
                        $('#success .toast-body').text(response.msg);
                        var successToast = new bootstrap.Toast(document.getElementById('success'));
                        successToast.show();
                        
                        // Optional: Redirect after delay
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.settings") }}';
                        }, 2000);
                    } else {
                        // Show error toast
                        $('#danger .toast-body').text(response.msg);
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.html(originalText).prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                        
                        // Show error toast for validation errors
                        $('#danger .toast-body').text('Please fix the validation errors below.');
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    } else {
                        // Other errors
                        $('#danger .toast-body').text('An error occurred. Please try again.');
                        var dangerToast = new bootstrap.Toast(document.getElementById('danger'));
                        dangerToast.show();
                    }
                }
            });
        });

        // Update tax rates in real-time
        function updateTaxSummary() {
            var sgst = parseFloat($('#setting_sgst').val()) || 0;
            var cgst = parseFloat($('#setting_cgst').val()) || 0;
            var igst = parseFloat($('#setting_igst').val()) || 0;
            
            // Update display
            $('#sgst_display').text(sgst.toFixed(2) + '%');
            $('#cgst_display').text(cgst.toFixed(2) + '%');
            $('#igst_display').text(igst.toFixed(2) + '%');
            
            // Update tax summary
            var intraStateTotal = sgst + cgst;
            var interStateTotal = igst;
            
            $('#total_intra_state').text(intraStateTotal.toFixed(2));
            $('#total_inter_state').text(interStateTotal.toFixed(2));
        }

        // Update tax summary when tax values change
        $('#setting_sgst, #setting_cgst, #setting_igst').on('input', function() {
            updateTaxSummary();
        });

        // Validate tax percentages (0-100)
        $('.tax-percentage').on('blur', function() {
            var value = parseFloat($(this).val());
            if (value < 0) {
                $(this).val(0);
            } else if (value > 100) {
                $(this).val(100);
            }
            updateTaxSummary();
        });

        // Auto-calculate CGST to match SGST (optional feature)
        $('#setting_sgst').on('blur', function() {
            var sgstValue = parseFloat($(this).val()) || 0;
            var cgstInput = $('#setting_cgst');
            
            // Auto-match CGST with SGST (they're usually the same)
            if (sgstValue > 0 && cgstInput.val() === '') {
                cgstInput.val(sgstValue);
                updateTaxSummary();
            }
        });

        // Preview signature image before upload
        $('#setting_signature').on('change', function() {
            var file = this.files[0];
            if (file) {
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    $(this).val('');
                    return;
                }

                // Validate file type
                var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Only JPG, JPEG, and PNG files are allowed');
                    $(this).val('');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview if any
                    $('.signature-preview').remove();
                    
                    // Create preview
                    var preview = $('<div class="signature-preview mt-2"></div>');
                    preview.append('<p class="mb-1"><small>Signature Preview:</small></p>');
                    preview.append('<img src="' + e.target.result + '" alt="Signature Preview" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">');
                    
                    // Insert after the file input
                    $('#setting_signature').after(preview);
                };
                reader.readAsDataURL(file);
            } else {
                // Remove preview if file input is cleared
                $('.signature-preview').remove();
            }
        });

        // Optional: Auto-format GST number
        $('#setting_gst_no').on('input', function() {
            var value = $(this).val().toUpperCase();
            // Remove all spaces
            value = value.replace(/\s/g, '');
            $(this).val(value);
        });

        // Optional: Auto-add https:// to website URL
        $('#setting_website_url').on('blur', function() {
            var url = $(this).val().trim();
            if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
                $(this).val('https://' + url);
            }
        });

        // Format phone number
        $('#setting_phone').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            $(this).val(value);
        });

        // Initialize tax summary on page load
        updateTaxSummary();
    });
</script>
@endpush