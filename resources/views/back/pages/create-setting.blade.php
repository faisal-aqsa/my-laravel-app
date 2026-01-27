
@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '' )

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Add Setting</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Setting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.store-setting') }}" method="POST" id="addSettingForm">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Name</label>
                                    <input type="text" class="form-control" id="setting_name" name="setting_name" placeholder="Enter Name"
                                    value="">
                                    <span class="text-danger error-text setting_name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Phone Number</label>
                                    <input type="text" class="form-control" id="setting_phone" name="setting_phone" placeholder="Enter Phone"
                                    value="">
                                    <span class="text-danger error-text setting_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Email</label>
                                    <input type="email" class="form-control" id="setting_email" name="setting_email" placeholder="Enter Email"
                                    value="">
                                    <span class="text-danger error-text setting_email_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">GST Number</label>
                                    <input type="text" class="form-control" id="setting_gst_no" name="setting_gst_no" placeholder="Enter Gst Number"
                                    value="">
                                    <span class="text-danger error-text setting_gst_no_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Website Url</label>
                                    <input type="text" class="form-control" id="_setting_website_url" name="_setting_website_url" placeholder="Enter Website Url"
                                    value="">
                                    <span class="text-danger error-text _setting_website_url_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Signature</label>
                                    <input type="file" class="form-control" id="setting_signature" name="setting_signature" placeholder="Enter Email"
                                    value="">
                                    <span class="text-danger error-text setting_signature_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-firstname-input">Address</label>
                                    <textarea name="setting_address" id="setting_address" class="form-control" placeholder="Enter Address"></textarea>
                                    <span class="text-danger error-text setting_address_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-primary w-md">Add Client</button>
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
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);

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
                        // Success
                        $(form)[0].reset();
                        
                        // Show success toast
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

        // Preview signature image before upload
        $('#setting_signature').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview if any
                    $('.signature-preview').remove();
                    
                    // Create preview
                    var preview = $('<div class="signature-preview mt-2"></div>');
                    preview.append('<p class="mb-1"><small>Preview:</small></p>');
                    preview.append('<img src="' + e.target.result + '" alt="Signature Preview" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">');
                    
                    // Insert after the file input
                    $('#setting_signature').after(preview);
                };
                reader.readAsDataURL(file);
            }
        });

        // Optional: Auto-format GST number
        $('#setting_gst_no').on('input', function() {
            var value = $(this).val().toUpperCase();
            // Remove all spaces
            value = value.replace(/\s/g, '');
            // Auto-format as 11AABCC1234Z1A5 or similar GST format
            if (value.length > 2) {
                value = value.substring(0, 2).toUpperCase() + value.substring(2);
            }
            $(this).val(value);
        });

        // Optional: Auto-format phone number
        $('#setting_phone').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            $(this).val(value);
        });

        // Optional: Validate website URL format
        $('#setting_website_url').on('blur', function() {
            var url = $(this).val();
            if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
                $(this).val('https://' + url);
            }
        });
    });
</script>

@endpush
