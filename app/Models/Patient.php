<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type_id',
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    /**
     * Get the user that owns the patient record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the blood type associated with the patient.
     */
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}
