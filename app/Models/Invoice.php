<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'invoice_number',
        'invoice_date',
        'status',
        'total_amount',
        'due_date',
        'grand_total',
        'paid_amount',
        'is_sgst',
        'is_cgst',
        'is_gst',
        'is_igst',
        'consignee_address',
        'e_way_bill_no',
        'vehicle_no',
        'po_number',
        'paid_date',
    ];
    
    public function getClient() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'is_sgst' => 'boolean',
        'is_cgst' => 'boolean',
        'is_gst' => 'boolean',
        'is_igst' => 'boolean',
        'total_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->paid_amount;
    }

    // Helper method to check if invoice is fully paid
    public function getIsFullyPaidAttribute()
    {
        return $this->grand_total > 0 && $this->grand_total <= $this->paid_amount;
    }

    // Helper method to get payment status
    public function getPaymentStatusAttribute()
    {
        if ($this->grand_total <= $this->paid_amount) {
            return 'Fully Paid';
        } elseif ($this->paid_amount > 0) {
            return 'Partially Paid';
        } else {
            return 'Unpaid';
        }
    }

    // Helper method to format vehicle number
    public function getFormattedVehicleNoAttribute()
    {
        if (!$this->vehicle_no) {
            return 'N/A';
        }
        
        // Format vehicle number (e.g., MH-12-AB-1234)
        $vehicleNo = strtoupper($this->vehicle_no);
        $vehicleNo = preg_replace('/\s+/', '', $vehicleNo);
        
        return $vehicleNo;
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
