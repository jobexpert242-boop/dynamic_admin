<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    protected $guarded = [];

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'invoice_id');
    }
}
