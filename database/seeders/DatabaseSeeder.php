<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\WasapiAccount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // password
        ]);

        //add wasapi account
        WasapiAccount::create([
            'phone' => '18098510363',
            'token' => '25903|e6Gg8KcLHVeryRk6qQxAaUWBzDa9xDFkVqoj01Bz',
            'wasapi_id' => '11640',
            'final_message' => '🧾✨  
            Tu participación en la promoción “Rumbo al Clásico 2026 con Schick” ha sido registrada exitosamente. 
             👉 Recuerda: Mientras más productos Schick compres y facturas registres, más oportunidades tendrás de ganar.',
        ]);
    }
}
