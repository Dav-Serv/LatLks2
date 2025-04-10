<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable =[
        'user_id',
        'table_id',
        'product_id',
        'name',
        'guests',
        'count',
        'subtotal',
        'date',
        'status_pay',
        'status_order',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function table() : BelongsTo {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function requestpay()
    {
        return $this->hasOne(RequestPay::class, 'order_id');
    }
}
