<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
        'description',
        'rating',
        'image_url',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
