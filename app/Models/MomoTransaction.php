<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomoTransaction extends Model
{
    use HasFactory;
    protected $table = 'momo_transactions';
    protected $fillable = [
        'order_id',
        'partner_code',
        'orderId',
        'requestId',
        'amount',
        'orderInfo',
        'orderType',
        'transId',
        'resultCode',
        'message',
        'payType',
        'responseTime',
        'extraData',
        'signature',
        'status',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
