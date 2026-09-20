<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemVariant extends Model
{
    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
