<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    public $timestamps = false;
    protected $fillable = ['workspace_id', 'user_id', 'action'];
    // columns: id, workspace_id, user_id, action, created_at
}
