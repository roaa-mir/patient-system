<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
    protected $fillable = [
        'date',
        'time',
        'patient_id',
        'doctor_id',
        'clinic_id',
        'billing_id'

    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class); 
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);  
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class); 
    }
    public function billing()
    {
        return $this->belongsTo(Billing::class);  
    }
}
