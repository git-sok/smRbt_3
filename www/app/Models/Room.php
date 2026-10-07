<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['room_number', 'floor', 'capacity'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
