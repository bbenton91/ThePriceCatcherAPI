<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrices extends Model
{
    use HasFactory;

    protected $table = 'product_prices'; //It wants to name the table 'most_vieweds' by default
    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    protected $fillable = [
        'product_sku', 'regular_price', 'sale_price'
    ];
}
