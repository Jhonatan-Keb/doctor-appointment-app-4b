<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'consultation_id',
        'medicine_name',
        'dosage',
        'frequency_duration',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}