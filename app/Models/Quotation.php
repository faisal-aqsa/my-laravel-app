<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'attention',
        'quotation_for',
        'date',
        'notes',
        'is_tax_included',
        'is_delivery_charges_included',
        'is_printing_included',
        'is_plate_and_punch',
        'is_lamination',
    ];

    protected $casts = [
        'date' => 'date',
        'is_tax_included' => 'boolean',
        'is_delivery_charges_included' => 'boolean',
        'is_printing_included' => 'boolean',
        'is_plate_and_punch' => 'boolean',
        'is_lamination' => 'boolean',
    ];

    // Relationship with client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with items
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}