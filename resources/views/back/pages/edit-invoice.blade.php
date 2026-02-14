@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Invoice #{{ $invoice->invoice_number }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.all-invoices') }}">Invoices</a></li>
                        <li class="breadcrumb-item active">Edit Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Tax Rates Display -->
    @php
        $settings = \App\Models\Setting::first();
    @endphp
    <div class="alert alert-info mb-3">
        <h6 class="alert-heading"><i class="fas fa-percentage me-2"></i> Current Tax Rates</h6>
        <div class="row mt-2">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2">SGST</span>
                    <span class="fw-medium">{{ $settings->sgst ?? 0 }}%</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2">CGST</span>
                    <span class="fw-medium">{{ $settings->cgst ?? 0 }}%</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-info me-2">IGST</span>
                    <span class="fw-medium">{{ $settings->igst ?? 0 }}%</span>
                </div>
            </div>
        </div>
        <small class="text-muted d-block mt-2">
            <i class="fas fa-info-circle me-1"></i>
            These rates are loaded from your settings. To change them, go to Settings page.
        </small>
    </div>

    <form action="{{ route('admin.update-invoice') }}" method="POST" id="editInvoiceForm">
        @csrf
        @method('POST')
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
        
        <!-- Hidden fields for tax rates -->
        <input type="hidden" id="sgst_rate" value="{{ $settings->sgst ?? 0 }}">
        <input type="hidden" id="cgst_rate" value="{{ $settings->cgst ?? 0 }}">
        <input type="hidden" id="igst_rate" value="{{ $settings->igst ?? 0 }}">
        
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-form-alerts></x-form-alerts>

                        <div class="row">
                            <!-- Client Selection -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="client_id">Client</label>
                                    <select name="client_id" id="client_id" class="form-select">
                                        <option value="">Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" 
                                                {{ $invoice->client_id == $client->id ? 'selected' : '' }}
                                                data-address="{{ $client->factory_address }}"
                                                data-gst="{{ $client->gst_no ?? '' }}">
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 

                            <!-- Invoice Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control" name="invoice_number" 
                                        id="invoice_number" value="{{ $invoice->invoice_number }}">
                                    @error('invoice_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 

                            <!-- Invoice Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="invoice_date">Invoice Date</label>
                                    <input type="date" class="form-control" name="invoice_date" 
                                        id="invoice_date" value="{{ $invoice->invoice_date->format('Y-m-d') }}">
                                    @error('invoice_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="due_date">Due Date</label>
                                    <input type="date" class="form-control" name="due_date" 
                                        id="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}">
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="po_number">PO Number</label>
                                    <input type="text" class="form-control" name="po_number" id="po_number" 
                                           value="{{ $invoice->po_number }}" placeholder="Enter Purchase Order Number">
                                    @error('po_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="vehicle_no">Vehicle No</label>
                                    <input type="text" class="form-control" name="vehicle_no" id="vehicle_no" 
                                           value="{{ $invoice->vehicle_no }}" placeholder="Enter Vehicle Number">
                                    @error('vehicle_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Tax Type Selection with Visual Rates -->
                            <div class="col-md-12">
                                <div class="card border mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Tax Type Selection</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium">Select Tax Type:</label>
                                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input tax-type" type="checkbox" id="is_sgst" name="is_sgst" value="1"
                                                                {{ $invoice->is_sgst ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_sgst">
                                                                <span class="badge bg-primary me-1">SGST</span> ({{ $settings->sgst ?? 0 }}%)
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input tax-type" type="checkbox" id="is_cgst" name="is_cgst" value="1"
                                                                {{ $invoice->is_cgst ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_cgst">
                                                                <span class="badge bg-success me-1">CGST</span> ({{ $settings->cgst ?? 0 }}%)
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input tax-type" type="checkbox" id="is_igst" name="is_igst" value="1"
                                                                {{ $invoice->is_igst ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_igst">
                                                                <span class="badge bg-info me-1">IGST</span> ({{ $settings->igst ?? 0 }}%)
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="is_gst" name="is_gst" value="1"
                                                                {{ $invoice->is_gst ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_gst">
                                                                <i class="fas fa-file-invoice-dollar me-1"></i> GST (Auto-Select SGST+CGST)
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="alert alert-light border">
                                                    <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Tax Calculation</h6>
                                                    <div id="tax_calculation_info" class="mt-2">
                                                        <p class="mb-1">No tax selected</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Select tax type to calculate tax amount
                                                        </small>
                                                    </div>
                                                    <div class="mt-2" id="selected_tax_info" style="display: none;">
                                                        <p class="mb-1 fw-medium"><i class="fas fa-check-circle text-success me-1"></i>Selected:</p>
                                                        <ul class="mb-0 ps-3" id="tax_selection_list"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Add Performa Invoice Checkbox Here -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_performa_invoice" name="is_performa_invoice" value="1"
                                                        {{ $invoice->is_performa_invoice ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-medium" for="is_performa_invoice">
                                                        <i class="fas fa-file-invoice me-2 text-warning"></i>
                                                        <span class="badge bg-warning text-dark me-2">Proforma</span>
                                                        This is a Performa Invoice
                                                    </label>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Proforma invoices are preliminary documents that show estimated costs but are not official invoices
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Consignee Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="consignee_address">Consignee/Delivery Address</label>
                                    <textarea name="consignee_address" id="consignee_address" class="form-control" 
                                              placeholder="Enter consignee/delivery address" rows="3">{{ $invoice->consignee_address }}</textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Leave empty to use client's address
                                    </small>
                                    @error('consignee_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="e_way_bill_no">E-Way Bill No</label>
                                    <input type="text" class="form-control" name="e_way_bill_no" id="e_way_bill_no" 
                                           value="{{ $invoice->e_way_bill_no }}" placeholder="Enter E-Way Bill Number">
                                    @error('e_way_bill_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Invoice Items -->
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Invoice Items</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0" id="invoice_items_table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="35%">Particular</th>
                                                    <th width="15%">HSN No</th>
                                                    <th width="15%">Quantity</th>
                                                    <th width="15%">Unit Price (₹)</th>
                                                    <th width="15%">Total Price (₹)</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoice_items_body">
                                                @foreach ($invoiceItems as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="particular[]" class="form-control form-control-sm" 
                                                                   value="{{ $item->particular }}" placeholder="Item description" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="hsn_no[]" class="form-control form-control-sm" 
                                                                   value="{{ $item->hsn_no }}" placeholder="HSN Code">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control form-control-sm quantity" 
                                                                   min="1" value="{{ $item->quantity }}" step="0.01" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="unit_price[]" class="form-control form-control-sm unit_price" 
                                                                   min="0" step="0.01" value="{{ $item->unit_price }}" placeholder="0.00" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="total_price[]" class="form-control form-control-sm total_price" 
                                                                   value="{{ number_format($item->quantity * $item->unit_price, 2, '.', '') }}" readonly>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="mt-3 text-center">
                                            <button type="button" id="add_item" class="btn btn-outline-primary">
                                                <i class="fas fa-plus me-2"></i>Add Item
                                            </button>
                                        </div>
                                        
                                        <!-- Amount Summary -->
                                        <div class="row mt-4">
                                            <div class="col-md-6 offset-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Amount Summary</h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <table class="table table-bordered mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td><strong>Subtotal:</strong></td>
                                                                    <td class="text-end"><span id="subtotal_amount">{{ number_format($invoice->total_amount, 2) }}</span></td>
                                                                </tr>
                                                                <tr id="sgst_row" @if(!$invoice->is_sgst) style="display: none;" @endif>
                                                                    <td>
                                                                        <strong>SGST (<span id="sgst_percent">{{ $settings->sgst ?? 0 }}</span>%):</strong>
                                                                    </td>
                                                                    <td class="text-end"><span id="sgst_amount">
                                                                        @if($invoice->is_sgst)
                                                                            {{ number_format(($invoice->total_amount * $settings->sgst) / 100, 2) }}
                                                                        @else
                                                                            0.00
                                                                        @endif
                                                                    </span></td>
                                                                </tr>
                                                                <tr id="cgst_row" @if(!$invoice->is_cgst) style="display: none;" @endif>
                                                                    <td>
                                                                        <strong>CGST (<span id="cgst_percent">{{ $settings->cgst ?? 0 }}</span>%):</strong>
                                                                    </td>
                                                                    <td class="text-end"><span id="cgst_amount">
                                                                        @if($invoice->is_cgst)
                                                                            {{ number_format(($invoice->total_amount * $settings->cgst) / 100, 2) }}
                                                                        @else
                                                                            0.00
                                                                        @endif
                                                                    </span></td>
                                                                </tr>
                                                                <tr id="igst_row" @if(!$invoice->is_igst) style="display: none;" @endif>
                                                                    <td>
                                                                        <strong>IGST (<span id="igst_percent">{{ $settings->igst ?? 0 }}</span>%):</strong>
                                                                    </td>
                                                                    <td class="text-end"><span id="igst_amount">
                                                                        @if($invoice->is_igst)
                                                                            {{ number_format(($invoice->total_amount * $settings->igst) / 100, 2) }}
                                                                        @else
                                                                            0.00
                                                                        @endif
                                                                    </span></td>
                                                                </tr>
                                                                <tr class="table-active">
                                                                    <td><strong>Grand Total (₹):</strong></td>
                                                                    <td class="text-end">
                                                                        <strong><span id="grand_total_display">{{ number_format($invoice->grand_total, 2) }}</span></strong>
                                                                    </td>
                                                                    <input type="hidden" name="grand_total" id="grand_total" value="{{ $invoice->grand_total }}">
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden fields for calculations -->
                                        <input type="hidden" name="total_amount" id="total_amount" value="{{ $invoice->total_amount }}">
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
                <i class="fas fa-save me-2"></i>Update Invoice
            </button>
            <a href="{{ route('admin.all-invoices') }}" class="btn btn-secondary w-md">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get DOM elements
        let invoiceItemsBody = document.getElementById('invoice_items_body');
        let addItemButton = document.getElementById('add_item');
        let subtotalAmount = document.getElementById('subtotal_amount');
        let grandTotalDisplay = document.getElementById('grand_total_display');
        let grandTotalInput = document.getElementById('grand_total');
        let totalAmountInput = document.getElementById('total_amount');
        let clientSelect = document.getElementById('client_id');
        let consigneeAddress = document.getElementById('consignee_address');
        
        // Tax rates from settings
        let sgstRate = parseFloat(document.getElementById('sgst_rate').value) || 0;
        let cgstRate = parseFloat(document.getElementById('cgst_rate').value) || 0;
        let igstRate = parseFloat(document.getElementById('igst_rate').value) || 0;
        
        // Tax checkboxes
        let isSgst = document.getElementById('is_sgst');
        let isCgst = document.getElementById('is_cgst');
        let isIgst = document.getElementById('is_igst');
        
        // Tax display elements
        let sgstRow = document.getElementById('sgst_row');
        let cgstRow = document.getElementById('cgst_row');
        let igstRow = document.getElementById('igst_row');
        let sgstPercent = document.getElementById('sgst_percent');
        let cgstPercent = document.getElementById('cgst_percent');
        let igstPercent = document.getElementById('igst_percent');
        let sgstAmount = document.getElementById('sgst_amount');
        let cgstAmount = document.getElementById('cgst_amount');
        let igstAmount = document.getElementById('igst_amount');
        let taxCalculationInfo = document.getElementById('tax_calculation_info');
        let selectedTaxInfo = document.getElementById('selected_tax_info');
        let taxSelectionList = document.getElementById('tax_selection_list');
        
        // Initialize tax percentage displays
        sgstPercent.textContent = sgstRate;
        cgstPercent.textContent = cgstRate;
        igstPercent.textContent = igstRate;

        // Proforma invoice toggle effects for edit page
        let isProformaCheckbox = document.getElementById('is_performa_invoice');

        if (isProformaCheckbox) {
            isProformaCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('.card-body').classList.add('bg-light');
            
                    // Optional: Add warning badge
                    let badge = document.createElement('span');
                    badge.className = 'badge bg-warning text-dark ms-2';
                    badge.id = 'proforma-badge';
                    badge.innerHTML = '<i class="fas fa-file-invoice me-1"></i>PROFORMA';
                    
                    // Add badge to page title if not exists
                    if (!document.getElementById('proforma-badge')) {
                        document.querySelector('.page-title-box h4').appendChild(badge);
                    }

                    // Disable E-Way Bill field for proforma invoices
                    document.getElementById('e_way_bill_no').closest('.col-md-6').style.opacity = '0.5';
                    document.getElementById('e_way_bill_no').readOnly = true;
                    document.getElementById('e_way_bill_no').placeholder = 'Not applicable for proforma';
                } else {
                    this.closest('.card-body').classList.remove('bg-light');
            
                    // Remove proforma badge
                    let badge = document.getElementById('proforma-badge');
                    if (badge) {
                        badge.remove();
                    }
                    // Re-enable E-Way Bill field
                    document.getElementById('e_way_bill_no').closest('.col-md-6').style.opacity = '1';
                    document.getElementById('e_way_bill_no').readOnly = false;
                    document.getElementById('e_way_bill_no').placeholder = 'Enter E-Way Bill Number';
                }
            });

            // Trigger on page load if it's already a proforma invoice
            if (isProformaCheckbox.checked) {
                isProformaCheckbox.dispatchEvent(new Event('change'));
            }
        }
        
        // Calculate total for a single row
        function calculateRowTotal(row) {
            let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            let unitPrice = parseFloat(row.querySelector('.unit_price').value) || 0;
            let total = quantity * unitPrice;
            row.querySelector('.total_price').value = total.toFixed(2);
            updateInvoiceSummary();
        }
        
        // Update tax selection info display
        function updateTaxSelectionInfo() {
            let selectedTaxes = [];
            
            if (isSgst.checked && isCgst.checked) {
                selectedTaxes.push(`SGST (${sgstRate}%) + CGST (${cgstRate}%) = Total ${(sgstRate + cgstRate)}%`);
                selectedTaxes.push('Applied for intra-state transactions');
            } else if (isIgst.checked) {
                selectedTaxes.push(`IGST (${igstRate}%)`);
                selectedTaxes.push('Applied for inter-state transactions');
            }
            
            if (selectedTaxes.length > 0) {
                taxCalculationInfo.style.display = 'none';
                selectedTaxInfo.style.display = 'block';
                taxSelectionList.innerHTML = '';
                
                selectedTaxes.forEach(tax => {
                    let li = document.createElement('li');
                    li.textContent = tax;
                    taxSelectionList.appendChild(li);
                });
            } else {
                taxCalculationInfo.style.display = 'block';
                selectedTaxInfo.style.display = 'none';
            }
        }
        
        // Update the entire invoice summary
        function updateInvoiceSummary() {
            let subtotal = 0;
            
            // Calculate subtotal from all items
            document.querySelectorAll('.total_price').forEach(item => {
                subtotal += parseFloat(item.value) || 0;
            });
            
            // Update subtotal display
            subtotalAmount.textContent = subtotal.toFixed(2);
            totalAmountInput.value = subtotal.toFixed(2);
            
            // Calculate taxes
            let sgstTax = 0;
            let cgstTax = 0;
            let igstTax = 0;
            let grandTotal = subtotal;
            
            // Update tax display and calculations
            if (isSgst.checked && isCgst.checked) {
                sgstRow.style.display = 'table-row';
                cgstRow.style.display = 'table-row';
                igstRow.style.display = 'none';
                
                sgstTax = (subtotal * sgstRate) / 100;
                cgstTax = (subtotal * cgstRate) / 100;
                
                sgstAmount.textContent = sgstTax.toFixed(2);
                cgstAmount.textContent = cgstTax.toFixed(2);
                
                grandTotal = subtotal + sgstTax + cgstTax;
            } 
            else if (isIgst.checked) {
                sgstRow.style.display = 'none';
                cgstRow.style.display = 'none';
                igstRow.style.display = 'table-row';
                
                igstTax = (subtotal * igstRate) / 100;
                igstAmount.textContent = igstTax.toFixed(2);
                
                grandTotal = subtotal + igstTax;
            } 
            else {
                sgstRow.style.display = 'none';
                cgstRow.style.display = 'none';
                igstRow.style.display = 'none';
                // No tax, grand total equals subtotal
            }
            
            // Update grand total
            grandTotalDisplay.textContent = grandTotal.toFixed(2);
            grandTotalInput.value = grandTotal.toFixed(2);
            
            // Update tax selection info
            updateTaxSelectionInfo();
            
            // Update tax checkboxes based on GST selection
            if (document.getElementById('is_gst').checked) {
                // If GST is checked, check all tax boxes
                isSgst.checked = true;
                isCgst.checked = true;
                isIgst.checked = false;
                updateInvoiceSummary(); // Recalculate
            }
        }
        
        // Add new item row
        addItemButton.addEventListener('click', function () {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="particular[]" class="form-control form-control-sm" placeholder="Item description" required></td>
                <td><input type="text" name="hsn_no[]" class="form-control form-control-sm" placeholder="HSN Code"></td>
                <td><input type="number" name="quantity[]" class="form-control form-control-sm quantity" min="1" value="1" step="0.01" required></td>
                <td><input type="number" name="unit_price[]" class="form-control form-control-sm unit_price" min="0" step="0.01" placeholder="0.00" required></td>
                <td><input type="number" name="total_price[]" class="form-control form-control-sm total_price" readonly></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-times"></i></button></td>
            `;
            invoiceItemsBody.appendChild(newRow);
        });
        
        // Remove item row
        invoiceItemsBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item') || event.target.closest('.remove-item')) {
                event.target.closest('tr').remove();
                updateInvoiceSummary();
            }
        });
        
        // Calculate row total when quantity or unit price changes
        invoiceItemsBody.addEventListener('input', function (event) {
            if (event.target.classList.contains('quantity') || event.target.classList.contains('unit_price')) {
                calculateRowTotal(event.target.closest('tr'));
            }
        });
        
        // Update tax calculation when tax checkboxes change
        document.querySelectorAll('.tax-type').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // If SGST or CGST is checked, uncheck IGST
                if ((this.id === 'is_sgst' || this.id === 'is_cgst') && this.checked) {
                    isIgst.checked = false;
                }
                // If IGST is checked, uncheck SGST and CGST
                if (this.id === 'is_igst' && this.checked) {
                    isSgst.checked = false;
                    isCgst.checked = false;
                }
                updateInvoiceSummary();
            });
        });
        
        // GST checkbox - checks all tax types
        document.getElementById('is_gst').addEventListener('change', function() {
            if (this.checked) {
                isSgst.checked = true;
                isCgst.checked = true;
                isIgst.checked = false;
                updateInvoiceSummary();
            } else {
                isSgst.checked = false;
                isCgst.checked = false;
                updateInvoiceSummary();
            }
        });
        
        // Auto-fill consignee address from client
        clientSelect.addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let clientAddress = selectedOption.getAttribute('data-address');
            
            // Auto-fill consignee address if empty
            if (clientAddress && !consigneeAddress.value.trim()) {
                consigneeAddress.value = clientAddress;
            }
        });
        
        // Auto-format vehicle number
        document.getElementById('vehicle_no').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            value = value.replace(/\s/g, '');
            e.target.value = value;
        });
        
        // Auto-format E-way bill number
        document.getElementById('e_way_bill_no').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            value = value.replace(/\s/g, '');
            e.target.value = value;
        });
        
        // Auto-format PO number
        document.getElementById('po_number').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
        
        // Form validation before submit
        document.getElementById('editInvoiceForm').addEventListener('submit', function(e) {
            // Validate at least one item exists
            let itemCount = document.querySelectorAll('#invoice_items_body tr').length;
            if (itemCount === 0) {
                e.preventDefault();
                alert('Please add at least one invoice item');
                return false;
            }
            
            // Validate all items have particulars
            let hasEmptyParticulars = false;
            document.querySelectorAll('input[name="particular[]"]').forEach(input => {
                if (!input.value.trim()) {
                    hasEmptyParticulars = true;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (hasEmptyParticulars) {
                e.preventDefault();
                alert('Please fill in all item descriptions');
                return false;
            }
            
            // Validate all items have unit price
            let hasEmptyUnitPrice = false;
            document.querySelectorAll('input[name="unit_price[]"]').forEach(input => {
                if (!input.value || parseFloat(input.value) <= 0) {
                    hasEmptyUnitPrice = true;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (hasEmptyUnitPrice) {
                e.preventDefault();
                alert('Please enter valid unit prices for all items');
                return false;
            }
            
            // Validate client is selected
            if (!clientSelect.value) {
                e.preventDefault();
                alert('Please select a client');
                clientSelect.style.borderColor = 'red';
                return false;
            }
            
            // Show loading state
            let submitButton = this.querySelector('button[type="submit"]');
            let originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating Invoice...';
            submitButton.disabled = true;
            
            return true;
        });
        
        // Initialize calculation
        updateInvoiceSummary();
        updateTaxSelectionInfo();
    });
</script>
@endpush