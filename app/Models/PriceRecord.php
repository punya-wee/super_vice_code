<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRecord extends Model
{
    protected $table = 'price_records';
    protected $fillable = ['product_name', 'category', 'price', 'recorded_date', 'source'];
    protected $casts = ['recorded_date' => 'date', 'price' => 'float'];
}
