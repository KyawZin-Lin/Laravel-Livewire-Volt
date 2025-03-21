<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleInformation extends Model
{
    protected $fillable = [
        'item_id',
        'selling_price',
        'sale_description',
        'sale_tax',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
