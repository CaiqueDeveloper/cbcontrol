<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order_product(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function who_received(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'who_received_id');
    }
    public function delivery_man(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'delivery_man_id');
    }
    public function order_hav(): HasMany
    {
        return $this->hasMany(OrderHav::class);
    }
    public function reason(): HasOne
    {
        return $this->hasOne('reason', 'id', 'reason_id');
    }
}
