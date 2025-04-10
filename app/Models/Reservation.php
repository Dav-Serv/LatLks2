<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'name',
        'guests',
        'telephone',
        'email',
        'date',
        'status_pay',
        'status_reser',
    ];

    protected $appends = [
        'payGate',
        'dava'
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id', );
    }

    public function table() : BelongsTo {
        return $this->belongsTo(Table::class, 'table_id', );
    }

    public function requestpay()
    {
        return $this->hasOne(RequestPay::class, 'reservation_id');
    }

    public function getPayGateAttribute() {
        try {
            if($this->requestpay->external_id) {
                if($pg = PayGate::where('external_id', $this->requestpay->external_id)->first()) {
                    return $pg->payment_channel;
                }
            }
        } catch (\Exception $e) {
            return "";
        }
    }

    public function getDavaAttribute() {
        try {
            return "Dava XII";
        } catch (\Exception $e) {
            return "Dava";
        }
    }
}


