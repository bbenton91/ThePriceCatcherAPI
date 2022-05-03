<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentlyChanged extends Model
{
    use HasFactory;

    protected $table = 'recently_changed'; //It wants to name the table 'recently_changeds' by default
    protected $primaryKey = 'product_sku';
    public $incrementing = false;

    protected $fillable = [
        'product_sku'
    ];
}
