<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryChallan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'challan_number',
        'client_id',
        'consignee_address',
        'vehicle_no',
        'challan_date',
        'total_amount',
    ];

    protected $casts = [
        'challan_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relationship with client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getClient() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    // Relationship with items
    public function items()
    {
        return $this->hasMany(DeliveryChallanItem::class);
    }
}