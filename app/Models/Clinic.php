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
        'is_active',
        'doctor_id'

    ];

    public function doctors(){
        return $this->hasMany(Doctor::class,'doctorID');
    }
    public function appointments(){
        return $this->hasMany(Appointment::class,'doctorID');
    }
}
