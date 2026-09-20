<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringRequest extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'catering_package_id');
    }
}
