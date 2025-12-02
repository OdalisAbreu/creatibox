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
            'final_message' => '¡Listo! Tu factura ha sido recibida correctamente. ✅

*¡Gracias por participar en Estrellas de la Navidad!* 🌟

Recuerda: mientras más facturas registres, más oportunidades tienes de ganar ese bar soñado para tu hogar. Promoción válida hasta el 10 de enero del 2026. Evite el exceso de alcohol. 

¡Mucha suerte! 🍀
',
        ]);
    }
}
