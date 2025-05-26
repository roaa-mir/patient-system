<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    protected $fillable = [
        'user_id',
        'firstName',
        'lastName',
        'dateOfBirth',
        'gender',
        'age',
        'bloodType',
        'phoneNumber',
        'address',
        'email',
        'weight',
        'height'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
    
    public function appointments(){
        return $this->hasMany(Appointment::class,'patientID');
    }
}
