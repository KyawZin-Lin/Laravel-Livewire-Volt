<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function saleInformation()
    {
        return $this->hasOne(SaleInformation::class);
    }
    public function purchaseInformation()
    {
        return $this->hasOne(PurchaseInformation::class);
    }
}
