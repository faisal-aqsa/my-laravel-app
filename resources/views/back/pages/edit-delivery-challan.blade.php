@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Delivery Challan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Delivery Challan #{{ $challan->challan_number }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.all-challans') }}">Delivery Challans</a></li>
                        <li class="breadcrumb-item active">Edit Delivery Challan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.update-delivery-challan', $challan->id) }}" method="POST" id="editDeliveryChallanForm">
        @csrf
        @method('POST')
        
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
                                    <select name="client_id" id="client_id" class="form-select" required>
                                        <option value="">Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" 
                                                    data-address="{{ $client->address }}"
                                                    {{ $challan->client_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 
                            
                            <!-- Challan Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="challan_number">Challan Number</label>
                                    <input type="text" class="form-control" name="challan_number" 
                                           id="challan_number" value="{{ $challan->challan_number }}">
                                    @error('challan_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 
                            
                            <!-- Challan Date -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="challan_date">Challan Date</label>
                                    <input type="date" class="form-control" name="challan_date" 
                                           id="challan_date" value="{{ $challan->challan_date->format('Y-m-d') }}" required>
                                    @error('challan_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Vehicle No -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="vehicle_no">Vehicle No</label>
                                    <input type="text" class="form-control" name="vehicle_no" id="vehicle_no" 
                                           value="{{ $challan->vehicle_no }}" placeholder="Enter Vehicle Number">
                                    @error('vehicle_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Delivery partner Phone No -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="delivery_partner_phone">Delivery partner Phone No</label>
                                    <input type="text" class="form-control" name="delivery_partner_phone" id="delivery_partner_phone" 
                                           value="{{ $challan->delivery_partner_phone }}" placeholder="Enter Vehicle Number">
                                    @error('delivery_partner_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Consignee Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="consignee_address">Consignee/Delivery Address</label>
                                    <textarea name="consignee_address" id="consignee_address" class="form-control" 
                                              placeholder="Enter consignee/delivery address" rows="3">{{ $challan->consignee_address }}</textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Leave empty to use client's address
                                    </small>
                                    @error('consignee_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Delivery Challan Items -->
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Delivery Challan Items</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0" id="challan_items_table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="40%">Particular</th>
                                                    <th width="20%">Quantity</th>
                                                    <th width="30%">Total Amount (₹)</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="challan_items_body">
                                                @foreach ($challanItems as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="particular[]" class="form-control form-control-sm" 
                                                                   value="{{ $item->particular }}" placeholder="Item description" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control form-control-sm quantity" 
                                                                   min="0" step="0.01" value="{{ $item->quantity }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="total_amount[]" class="form-control form-control-sm item-total" 
                                                                   min="0" step="0.01" value="{{ $item->total_amount }}" required>
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
                                        
                                        <!-- Total Amount Summary -->
                                        <div class="row mt-4">
                                            <div class="col-md-6 offset-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Amount Summary</h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <table class="table table-bordered mb-0">
                                                            <tbody>
                                                                <tr class="table-active">
                                                                    <td><strong>Total Amount (₹):</strong></td>
                                                                    <td class="text-end">
                                                                        <strong><span id="total_amount_display">{{ number_format($challan->total_amount, 2) }}</span></strong>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
        </div>
        <div class="mb-2 mt-3">
            <button type="submit" class="btn btn-primary w-md">
                <i class="fas fa-save me-2"></i>Update Delivery Challan
            </button>
            <a href="{{ route('admin.all-challans') }}" class="btn btn-secondary w-md">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    // Same JavaScript as create page
    document.addEventListener('DOMContentLoaded', function () {
        let challanItemsBody = document.getElementById('challan_items_body');
        let addItemButton = document.getElementById('add_item');
        let totalAmountDisplay = document.getElementById('total_amount_display');
        let clientSelect = document.getElementById('client_id');
        let consigneeAddress = document.getElementById('consignee_address');
        
        function updateTotalAmount() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(item => {
                total += parseFloat(item.value) || 0;
            });
            totalAmountDisplay.textContent = total.toFixed(2);
        }
        
        addItemButton.addEventListener('click', function () {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="particular[]" class="form-control form-control-sm" placeholder="Item description" required></td>
                <td><input type="number" name="quantity[]" class="form-control form-control-sm quantity" min="0" step="0.01" value="1" required></td>
                <td><input type="number" name="total_amount[]" class="form-control form-control-sm item-total" min="0" step="0.01" placeholder="0.00" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-times"></i></button></td>
            `;
            challanItemsBody.appendChild(newRow);
        });
        
        challanItemsBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item') || event.target.closest('.remove-item')) {
                event.target.closest('tr').remove();
                updateTotalAmount();
            }
        });
        
        challanItemsBody.addEventListener('input', function (event) {
            if (event.target.classList.contains('item-total')) {
                updateTotalAmount();
            }
        });
        
        clientSelect.addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let clientAddress = selectedOption.getAttribute('data-address');
            if (clientAddress && !consigneeAddress.value.trim()) {
                consigneeAddress.value = clientAddress;
            }
        });
        
        document.getElementById('vehicle_no').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            value = value.replace(/\s/g, '');
            e.target.value = value;
        });
        
        document.getElementById('editDeliveryChallanForm').addEventListener('submit', function(e) {
            let itemCount = document.querySelectorAll('#challan_items_body tr').length;
            if (itemCount === 0) {
                e.preventDefault();
                alert('Please add at least one item');
                return false;
            }
            
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
            
            let hasEmptyTotal = false;
            document.querySelectorAll('input[name="total_amount[]"]').forEach(input => {
                if (!input.value || parseFloat(input.value) < 0) {
                    hasEmptyTotal = true;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (hasEmptyTotal) {
                e.preventDefault();
                alert('Please enter valid total amounts for all items');
                return false;
            }
            
            if (!clientSelect.value) {
                e.preventDefault();
                alert('Please select a client');
                clientSelect.style.borderColor = 'red';
                return false;
            }
            
            let submitButton = this.querySelector('button[type="submit"]');
            let originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
            submitButton.disabled = true;
            
            return true;
        });
        
        updateTotalAmount();
    });
</script>
@endpush