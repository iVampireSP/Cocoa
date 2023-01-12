<?php

namespace ivampiresp\Cocoa\Models;

use App\Actions\HostAction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class Host extends Model
{
    use HasFactory;

    public mixed $user_id;
    public mixed $price;
    public mixed $host_id;
    public mixed $id;
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
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $http = Http::remote('remote')->asForm();
            // if id exists
            if ($model->where('id', $model->id)->exists()) {
                return false;
            }

            if ($model->price === null) {
                $model->price = $model->calcPrice();
            }

            if ($model->user_id === null) {
                $model->user_id = auth('user')->id();
            }

            // 云端 Host 应该在给 Model 创建数据之前被创建。

            // 云端 Host 创建后，再到这里计算价格。
            $http->patch('/hosts/' . $model->host_id, [
                'price' => $model->price
            ]);
        });

        // update
        static::updating(function ($model) {
            $http = Http::remote('remote')->asForm();


            if ($model->status == 'suspended') {
                $model->suspended_at = now();
            } else if ($model->status == 'running') {
                $model->suspended_at = null;
            }

            $pending = [];

            if ($model->isDirty('price')) {
                $pending['price'] = $model->price;
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


        static::updated(function ($model) {
            if ($model->isDirty('status')) {
                $pending['status'] = $model->status;

                $hostAction = new HostAction();

                // 如果方法在 hostAction 中存在，就调用它。
                if (method_exists($hostAction, $model->status)) {
                    $hostAction->{$model->status}($model);
                }
            }
        });
    }

    // scope thisUser

    public function calcPrice()
    {

        // 你可以自定义价格计算器。

        // $this->load('location');
        $price = 0;
        // $price += $this->location->price;
        // $price += ($this->cpu_limit / 100) * $this->location->cpu_price;
        // $price += ($this->memory) *
        //     $this->location->memory_price;
        // $price += ($this->disk / 1024) *
        //     $this->location->disk_price;
        // $price += $this->backups *
        //     $this->location->backup_price;
        // $price += $this->allocations *
        //     $this->location->allocation_price;
        // $price += $this->databases *
        //     $this->location->database_price;

        return $price;
    }


    // user

    public function getRouteKeyName()
    {
        return 'host_id';
    }

    // workOrders

    public function scopeThisUser($query, $user_id = null)
    {
        $user_id = $user_id ?? request('user_id');
        return $query->where('user_id', $user_id);
    }

    // scope

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running')->where('price', '!=', 0);
    }

    public function getPrice()
    {
        return $this->managed_price ?? $this->price;
    }
}
