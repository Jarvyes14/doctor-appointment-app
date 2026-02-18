<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type_id',
        'medical_history',
        'allergies',
        'date_of_birth',
        'gender',
        'occupation',
        'family_medical_history',
        'chronic_diseases',
        'medications',
        'general_health_notes',
        'smoker',
        'drinker',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
