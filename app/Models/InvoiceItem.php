<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'unit_price',
        'amount',
    ];

    // Relationship with Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relationship with Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
