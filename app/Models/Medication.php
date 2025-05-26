<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    //
    protected $fillable = [
        'name',
        'doses',
        'startDate',
        'endDate',
        'appointment_id',
        'patient_id'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class); 
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);  
    }
}
