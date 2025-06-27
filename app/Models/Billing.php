<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    //
    protected $fillable = [
        'date',
        'time',
        'amount',
        'status',
        'appointment_id'

    ];

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'Billing_id');
    }
}
