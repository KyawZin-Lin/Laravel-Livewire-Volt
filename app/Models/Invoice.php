<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    public function item():BelongsTo{
        return $this->belongsTo(Item::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
