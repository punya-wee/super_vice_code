<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;  // only has created_at
    protected $fillable = ['workspace_id', 'name', 'category', 'unit', 'description'];

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'product_id');
    }
}
