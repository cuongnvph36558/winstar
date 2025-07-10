<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutPage extends Model
{
    use HasFactory;

    protected $table = 'about_page';

    protected $fillable = [
        'title',
        'content',
    ];

    public $timestamps = true;
}
