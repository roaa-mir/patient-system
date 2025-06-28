<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    //
    protected $fillable = [
        'titlee',
        'date',
        'time',
        'amount',
        'status',
        'appointment_id'

    ];

    public function appointment()
{
    return $this->belongsTo(Appointment::class);
}
}
