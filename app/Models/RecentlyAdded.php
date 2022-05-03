<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentlyAdded extends Model
{
    use HasFactory;

    protected $table = 'recently_added'; //It wants to name the table 'recently_addeds' by default
    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    protected $fillable = [
        'product_sku'
    ];
}
