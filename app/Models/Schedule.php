<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $table = 'schedules';
    public $timestamps = false;
    protected $fillable = ['workspace_id', 'product_id', 'title', 'start_date', 'end_date', 'notes', 'created_by'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // คำนวณ status จากวันที่
    public function getStatusAttribute()
    {
        $today = Carbon::today();
        if (Carbon::parse($this->start_date)->gt($today))
            return 'วางแผนแล้ว';
        if (Carbon::parse($this->end_date)->lt($today))
            return 'เก็บเกี่ยวแล้ว';
        return 'กำลังปลูก';
    }
}
