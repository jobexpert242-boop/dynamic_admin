<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'invoice_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(BillingItem::class, 'invoice_id');
    }
}
