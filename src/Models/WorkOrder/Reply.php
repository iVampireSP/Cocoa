<?php

namespace ivampiresp\Cocoa\Models\WorkOrder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ivampiresp\Cocoa\Models\User;

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

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWorkOrderId($query, $work_order_id)
    {
        return $query->where('work_order_id', $work_order_id);
    }
}
