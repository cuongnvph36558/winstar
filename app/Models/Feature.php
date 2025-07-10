<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['title', 'subtitle', 'image'];

    public function items()
    {
        return $this->hasMany(FeatureItem::class);
    }
}
