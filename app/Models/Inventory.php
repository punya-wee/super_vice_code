<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    public $timestamps = false;
    protected $fillable = ['product_id', 'quantity', 'min_stock_level'];
    protected $casts = ['quantity' => 'float', 'min_stock_level' => 'float'];
}
