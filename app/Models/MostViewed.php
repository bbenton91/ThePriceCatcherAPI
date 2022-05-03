<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MostViewed extends Model
{
    use HasFactory;

    protected $table = 'most_viewed'; //It wants to name the table 'most_vieweds' by default
    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    public $fillable = ['product_sku', 'counter'];
}
