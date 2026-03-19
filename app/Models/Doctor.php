<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'speciality_id',
        'medical_license_number',
        'biography',
    ];
    // Un doctor pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Un doctor tiene una especialidad
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    // Un doctor tiene muchas citas
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Un doctor tiene muchos horarios
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}