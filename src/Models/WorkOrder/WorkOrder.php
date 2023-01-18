<?php

namespace ivampiresp\Cocoa\Models\WorkOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ivampiresp\Cocoa\Models\Host;
use ivampiresp\Cocoa\Models\User;

class WorkOrder extends Model
{
    public $incrementing = false;
    protected $table = 'work_orders';
    protected $fillable = [
        'id',
        'title',
        'content',
        'host_id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->where('id', $model->id)->exists()) {
                return false;
            }

            return true;
        });
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
