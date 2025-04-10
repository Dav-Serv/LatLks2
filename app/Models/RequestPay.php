<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;
use App\Models\Reservation;

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
        'order_id',
    ];

    public function reservation() : BelongsTo {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
