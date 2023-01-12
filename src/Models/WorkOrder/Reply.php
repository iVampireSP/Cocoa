<?php

namespace ivampiresp\Cocoa\Models\WorkOrder;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    public $incrementing = false;

    protected $table = 'work_order_replies';

    protected $fillable = [
        'id',
        'content',
        'work_order_id',
        'user_id',
        'is_pending',
        'created_at',
        'updated_at',
        'role'
    ];

    // public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // if id exists
            if ($model->where('id', $model->id)->exists()) {
                return false;
            }
        });
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWorkOrderId($query, $work_order_id)
    {
        return $query->where('work_order_id', $work_order_id);
    }
}
