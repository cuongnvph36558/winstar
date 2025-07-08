<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureItem extends Model
{
    protected $fillable = ['feature_id', 'icon', 'title', 'description'];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
