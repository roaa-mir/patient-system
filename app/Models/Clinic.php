<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    //
    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'email',
        'facilities',
        'working_hours',
        'is_active'
    ];

    public function doctors(){
        return $this->belongsToMany(Doctor::class,'clinic_doctor', 'clinic_id', 'doctor_id');
    }
    public function appointments(){
        return $this->hasMany(Appointment::class,'doctorID');
    }
}
