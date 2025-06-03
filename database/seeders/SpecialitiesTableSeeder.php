<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Specialitie;

class SpecialitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(){
        Specialitie::create(['title' => 'Cardiology']);
        Specialitie::create(['title' => 'Dermatology']);
        Specialitie::create(['title' => 'Neurology']);  
    
        
        // Add more specialities as needed




        //DB::table('specialities')->where('id', '>', 0)->delete();
        //DB::statement('ALTER TABLE specialities AUTO_INCREMENT = 1');
    }
}

