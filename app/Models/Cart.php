<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }

    // Alias for cartDetails
    public function details()
    {
        return $this->cartDetails();
    }
}
