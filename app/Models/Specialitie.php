<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialitie extends Model
{
    //
    protected $fillable = [
        'title'
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
