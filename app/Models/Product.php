<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];

    public function getShowImageAttribute()
    {
        return $this->image ? $this->image : 'assets/image/no-image.png';
    }
}
