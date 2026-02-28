<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantingPlan extends Model
{
    protected $table = 'planting_plans';
    protected $fillable = ['workspace_id', 'crop_name', 'category', 'plant_date', 'harvest_date', 'area_rai', 'status', 'notes', 'created_by'];
    protected $casts = ['plant_date' => 'date', 'harvest_date' => 'date', 'area_rai' => 'float'];
}
