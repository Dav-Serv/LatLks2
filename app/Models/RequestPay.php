<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestPay extends Model
{
    protected $fillable = [
        'external_id',
        'user_id',
        'status',
        'merchant_name',
        'merchant_profile_picture_url',
        'amount',
        'payer_email',
        'description',
        'expiry_date',
        'invoice_url',
        'reservation_id',
    ];

    public function reservation() : BelongsTo {
        return $this->belongsTo(Reservation::class, 'reservation_id', );
    }
}
