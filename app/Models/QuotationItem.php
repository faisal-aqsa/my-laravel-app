<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'particular',
        'gsm',
        'base_price',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    // Relationship with quotation
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}