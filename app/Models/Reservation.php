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
        'status',
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
}


