<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //4
    protected $fillable = [
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'blood_type_id',
    ];
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    //Relacion uno a uno inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relacion uno a uno inversa
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}
