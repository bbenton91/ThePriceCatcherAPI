<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    protected $fillable = [
        'product_sku', 'product_name', 'description', 'regular_price', 'sale_price', 'product_url', 'image_url', 'department_id'
    ];
}
