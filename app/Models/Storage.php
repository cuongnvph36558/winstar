<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'capacity',
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'storage_id', 'id');
    }
}
