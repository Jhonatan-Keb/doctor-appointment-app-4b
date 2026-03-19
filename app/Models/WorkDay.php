<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    protected $fillable = [
        'doctor_id',
        'day',
        'active',
        'slots',
    ];

    protected $casts = [
        'slots' => 'array',
        'active' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}