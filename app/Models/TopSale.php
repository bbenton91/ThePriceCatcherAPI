<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopSale extends Model
{
    use HasFactory;

    protected $table = 'top_sales';
    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    protected $fillable = [
        'product_sku'
    ];
}
