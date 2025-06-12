<?php


namespace App\Models;

//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
     //use HasApiTokens, Notifiable;
    //
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
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
        return $this->hasMany(Appointment::class,'patient_id');
    }
    public function clinics(){
    return $this->belongsToMany(Clinic::class, 'clinic_doctor', 'doctor_id', 'clinic_id');
}

    
    
}
