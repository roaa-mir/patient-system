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
        'status'

    ];

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
