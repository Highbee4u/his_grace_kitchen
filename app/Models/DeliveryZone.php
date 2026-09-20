<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['areas' => 'json', 'allow_pay_on_delivery' => 'boolean', 'is_active' => 'boolean'];
    }
}
