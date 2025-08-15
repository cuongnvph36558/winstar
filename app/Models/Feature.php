<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'image'];

    public function items()
    {
        return $this->hasMany(FeatureItem::class);
    }
}
