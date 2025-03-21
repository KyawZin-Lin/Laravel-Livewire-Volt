<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInformation extends Model
{
    //

    protected $fillable = [
        'item_id',
        'cost_price',
        'purchase_description',
        'purchase_tax',
        'preferred_vendor',
    ];
}
