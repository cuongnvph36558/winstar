<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
    ];

    protected $dates = ['deleted_at'];
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
