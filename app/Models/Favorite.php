<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use SoftDeletes;

    protected $table = 'favorites';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Get the user who favorited the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that is favorited.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}