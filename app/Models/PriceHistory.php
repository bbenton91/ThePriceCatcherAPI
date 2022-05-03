<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    // protected $primaryKey = 'product_sku';
    // public $incrementing = false;

    protected $fillable = [
        'product_sku', 'start_date', 'regular_price', 'sale_price'
    ];
}
