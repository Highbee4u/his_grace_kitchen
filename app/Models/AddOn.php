<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['menu_item_ids' => 'json', 'is_available' => 'boolean'];
    }
}
