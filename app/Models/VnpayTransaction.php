<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnpayTransaction extends Model
{
    use HasFactory;

    protected $table = 'vnpay_transactions';

    protected $fillable = [
        'order_id',
        'vnp_TxnRef',
        'vnp_Amount',
        'vnp_ResponseCode',
        'vnp_TransactionNo',
        'vnp_PayDate',
        'vnp_BankCode',
        'vnp_CardType',
        'vnp_SecureHash',
        'status',
        'message',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
} 