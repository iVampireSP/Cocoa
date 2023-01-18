<?php

namespace ivampiresp\Cocoa\Models;

use App\Actions\HostAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class Host extends Model
{
    protected $table = 'hosts';

    protected $fillable = [
        'id',
        'name',
        'user_id',
        'host_id',
        'price',
        'managed_price',
        'status',
    ];

    protected $casts = [
        'configuration' => 'array',
        'suspended_at' => 'datetime',
        'price' => 'decimal:2',
        'managed_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            $http = Http::remote()->asForm();

            if ($model->where('id', $model->id)->exists()) {
                return false;
            }

            if ($model->price === null) {
                $model->price = $model->calcPrice();
            }

            if ($model->user_id === null) {
                $model->user_id = auth('api')->id();
            }

            // 云端 Host 应该在给 Model 创建数据之前被创建。

            // 云端 Host 创建后，再到这里计算价格。
            $http->patch('/hosts/' . $model->host_id, [
                'price' => $model->price
            ]);

            return true;
        });

        // update
        static::updating(function (self $model) {

            $http = Http::remote()->asForm();

            if ($model->status == 'suspended') {
                $model->suspended_at = now();
            } else if ($model->status == 'running') {
                $model->suspended_at = null;
            }

            $pending = [];

            if ($model->isDirty('price')) {
                $pending['price'] = $model->price;
            } else {
                $pending['price'] = $model->calcPrice();
            }

            if ($model->isDirty('managed_price')) {
                $pending['managed_price'] = $model->managed_price;
            }

            if ($model->isDirty('status')) {
                $pending['status'] = $model->status;
            }

            if ($model->isDirty('name')) {
                $pending['name'] = $model->name;
            }

            if (count($pending) > 0) {
                $http->patch('/hosts/' . $model->host_id, $pending);
            }
        });


        static::updated(function (self $model) {
            if ($model->isDirty('status')) {
                $hostAction = new HostAction();

                // 如果方法在 hostAction 中存在，就调用它。
                if (method_exists($hostAction, $model->status)) {
                    $hostAction->{$model->status}($model);
                }
            }
        });
    }


    public function calcPrice(): string
    {
        // 价格计算机会在主机创建和更新时被调用。
        // 你可以自定义价格计算器，但是请切记，使用 bcmath 函数来计算价格，价格必须是字符串类型。
        $price = "0";

        /* 以下都是例子，请根据自己的需要来。 */
        // 加法
        $price = bcadd($price, "1", 2);
        // 除法
        $price = bcdiv($price, "2", 2);
        // 乘法
        $price = bcmul($price, "2", 2);

        // 判断是否为 0
        if (bccomp($price, "0", 2) === 0) {
            // 如果为 0，就返回 0.01
            return "0.01";
        }
        /* 以上都是例子，请根据自己的需要来。 */

        return $price;
    }

    public function getRouteKeyName(): string
    {
        return 'host_id';
    }

    public function scopeThisUser($query, $user_id = null)
    {
        $user_id = $user_id ?? request('user_id');
        return $query->where('user_id', $user_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running')->where('price', '!=', 0);
    }

    public function getPrice(): string
    {
        return $this->managed_price ?? $this->price;
    }
}
