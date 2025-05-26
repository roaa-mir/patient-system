<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //
    protected $fillable = [
        'firstname',
        'lastname',
        'address',
        'phoneNumber',
        'gender',
        'specialitie_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
    public function specialitie()
    {
        return $this->belongsTo(Specialitie::class,'specialitie_id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class,'patientID');
    }
    public function clinics(){
        return $this->hasMany(Clinic::class,'clinicID');
    }
    
    
}
