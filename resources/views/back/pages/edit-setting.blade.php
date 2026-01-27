@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Settings' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.settings') }}">Settings</a></li>
                        <li class="breadcrumb-item active">Edit Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.update-setting') }}" method="POST" id="editSettingForm" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        @method('POST')
                        <input type="hidden" name="setting_id" value="{{ $setting->id }}">
                        
                        <div class="row">
                            <!-- Company Details -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_name">Name</label>
                                    <input type="text" class="form-control" id="setting_name" name="setting_name" 
                                           value="{{ old('setting_name', $setting->name) }}"
                                           placeholder="Enter Company Name">
                                    <span class="text-danger error-text setting_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_phone">Phone Number</label>
                                    <input type="text" class="form-control" id="setting_phone" name="setting_phone" 
                                           value="{{ old('setting_phone', $setting->phone) }}"
                                           placeholder="Enter Phone">
                                    <span class="text-danger error-text setting_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_email">Email</label>
                                    <input type="email" class="form-control" id="setting_email" name="setting_email" 
                                           value="{{ old('setting_email', $setting->email) }}"
                                           placeholder="Enter Email">
                                    <span class="text-danger error-text setting_email_error"></span>
                                </div>
                            </div>
                            
                            <!-- GST Details -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_gst_no">GST Number</label>
                                    <input type="text" class="form-control" id="setting_gst_no" name="setting_gst_no" 
                                           value="{{ old('setting_gst_no', $setting->gst_no) }}"
                                           placeholder="Enter GST Number">
                                    <span class="text-danger error-text setting_gst_no_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_website_url">Website URL</label>
                                    <input type="text" class="form-control" id="setting_website_url" name="setting_website_url" 
                                           value="{{ old('setting_website_url', $setting->website_url) }}"
                                           placeholder="Enter Website URL">
                                    <span class="text-danger error-text setting_website_url_error"></span>
                                </div>
                            </div>
                            
                            <!-- Signature -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_signature">Signature</label>
                                    <input type="file" class="form-control" id="setting_signature" name="setting_signature" accept="image/*">
                                    <span class="text-danger error-text setting_signature_error"></span>
                                    
                                    @if($setting->signature)
                                        <div class="signature-preview mt-2">
                                            <p class="mb-1"><small>Current Signature:</small></p>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $setting->signature) }}" 
                                                     alt="Current Signature" 
                                                     class="img-thumbnail me-2"
                                                     style="max-width: 150px; max-height: 80px;">
                                                <!-- <div>
                                                    <small class="d-block text-muted">{{ basename($setting->signature) }}</small>
                                                    <a href="{{ asset('storage/' . $setting->signature) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary mt-1">
                                                        <i class="fas fa-eye"></i> View Full
                                                    </a>
                                                </div> -->
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <p class="text-muted mb-0"><small>No signature uploaded yet</small></p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Tax Rates -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_sgst">SGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="setting_sgst" name="setting_sgst" 
                                               value="{{ old('setting_sgst', $setting->sgst) }}"
                                               placeholder="Enter SGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_sgst_error"></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_cgst">CGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="setting_cgst" name="setting_cgst" 
                                               value="{{ old('setting_cgst', $setting->cgst) }}"
                                               placeholder="Enter CGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_cgst_error"></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_igst">IGST (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="setting_igst" name="setting_igst" 
                                               value="{{ old('setting_igst', $setting->igst) }}"
                                               placeholder="Enter IGST %" step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-danger error-text setting_igst_error"></span>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="setting_address">Address</label>
                                    <textarea name="setting_address" id="setting_address" class="form-control" 
                                              placeholder="Enter Address" rows="3">{{ old('setting_address', $setting->address) }}</textarea>
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
                                                        <span id="sgst_display">{{ old('setting_sgst', $setting->sgst) ?? 0 }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-success me-2">CGST</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span id="cgst_display">{{ old('setting_cgst', $setting->cgst) ?? 0 }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-info me-2">IGST</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span id="igst_display">{{ old('setting_igst', $setting->igst) ?? 0 }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="alert alert-info mt-2 mb-0">
                                                    <small>
                                                        <i class="fas fa-info-circle"></i> 
                                                        For intra-state transactions: CGST + SGST = {{ (old('setting_cgst', $setting->cgst) ?? 0) + (old('setting_sgst', $setting->sgst) ?? 0) }}%
                                                        <br>
                                                        For inter-state transactions: IGST = {{ old('setting_igst', $setting->igst) ?? 0 }}%
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
                <i class="fas fa-save"></i> Update Settings
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
        // Form submission
        $("#editSettingForm").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);

            // Reset error messages
            $(form).find('span.error-text').text('');

            // Show loading state
            var submitBtn = $(form).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

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
                        
                        // Update preview if new signature was uploaded
                        if (response.signature_path) {
                            $('.signature-preview img').attr('src', response.signature_path + '?t=' + new Date().getTime());
                        }
                        
                        // Update tax summary
                        if (response.sgst !== undefined) {
                            $('#sgst_display').text(response.sgst + '%');
                            $('#setting_sgst').val(response.sgst);
                        }
                        if (response.cgst !== undefined) {
                            $('#cgst_display').text(response.cgst + '%');
                            $('#setting_cgst').val(response.cgst);
                        }
                        if (response.igst !== undefined) {
                            $('#igst_display').text(response.igst + '%');
                            $('#setting_igst').val(response.igst);
                        }
                        
                        // Update tax summary calculation
                        updateTaxSummary();
                        
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
                    } else if (xhr.status === 404) {
                        // Not found error
                        $('#danger .toast-body').text('Settings not found. Please refresh the page.');
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
            
            $('.alert-info small').html(
                '<i class="fas fa-info-circle"></i> ' +
                'For intra-state transactions: CGST + SGST = ' + intraStateTotal.toFixed(2) + '%' +
                '<br>' +
                'For inter-state transactions: IGST = ' + interStateTotal.toFixed(2) + '%'
            );
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
            
            // Optional: Auto-match CGST with SGST if they're usually the same
            if (cgstInput.val() === '' && sgstValue > 0) {
                if (confirm('Do you want to set CGST same as SGST (' + sgstValue + '%)?')) {
                    cgstInput.val(sgstValue);
                    updateTaxSummary();
                }
            }
        });

        // Preview new signature image before upload
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
                    // Create or update preview
                    if ($('.new-signature-preview').length > 0) {
                        $('.new-signature-preview img').attr('src', e.target.result);
                    } else {
                        var preview = $('<div class="new-signature-preview mt-2"></div>');
                        preview.append('<p class="mb-1"><small>New Signature Preview:</small></p>');
                        preview.append('<img src="' + e.target.result + '" alt="New Signature Preview" style="max-width: 150px; max-height: 80px; border: 2px solid #28a745; padding: 3px;">');
                        preview.append('<p class="text-success mt-1"><small><i class="fas fa-info-circle"></i> New signature will replace the current one</small></p>');
                        
                        // Insert after the file input
                        $('#setting_signature').after(preview);
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Remove new signature preview if file input is cleared
                $('.new-signature-preview').remove();
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
        
        // Initialize tax summary on page load
        updateTaxSummary();
    });
</script>
@endpush