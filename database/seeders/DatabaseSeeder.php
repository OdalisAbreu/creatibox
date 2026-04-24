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
            'phone' => '18099901415',
            'token' => '413599|jpNfvfxSVkncwM54EJl6WuJpuYpvzw3CEOGl38Ro9b350a1a',
            'wasapi_id' => '21517',
            'final_message' => '¡Listo! Tu factura ha sido recibida correctamente. ✅

¡Gracias por participar en *9 Meses de Madre!* 🌟 Recuerda que mientras más facturas registres, más oportunidades tienes de ganar uno de los *100 premios de 9 meses de productos gratis.*

Promoción válida del *1 al 31 de mayo de 2026.*

¡Mucha suerte! 🍀✨',
        ]);
    }
}
