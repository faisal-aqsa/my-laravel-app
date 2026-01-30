<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_challan_id',
        'particular',
        'quantity',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationship with delivery challan
    public function deliveryChallan()
    {
        return $this->belongsTo(DeliveryChallan::class);
    }
}