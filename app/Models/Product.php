<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

//! fillble input
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'prod_code',
        'name',
        'description',
        'image_url',
        'unit_price',
        'cate_id',
        'brands_id',
    ];

    protected $casts = [
        'image_url' => 'json',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
