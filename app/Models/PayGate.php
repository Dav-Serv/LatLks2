<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayGate extends Model
{
    protected $fillable = [
        'external_id',
        'user_id',
        'is_high',
        'payment_method',
        'status',
        'merchant_name',
        'amount',
        'paid_amount',
        'bank_code',
        'paid_at',
        'payer_email',
        'description',
        'adjusted_received_amount',
        'fees_paid_amount',
        'currency',
        'payment_channel',
        'payment_destination',
    ];
}
