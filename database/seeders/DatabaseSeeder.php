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
            'phone' => '18093847847',
            'token' => '65853|X7le98KtKB5nKCj2ZavGgDrwAYjVIrK34uC6oI2f',
            'wasapi_id' => '12174',
            'final_message' => 'Ya estás a bordo del viaje de tus sueños con Santal! Gracias por registrarte en nuestra gran promoción. Esta es tu oportunidad de vivir unas vacaciones inolvidables en familia. Cada factura que registres es un paso más cerca de la aventura. La promoción estará activa hasta el *15 de septiembre del 2025*, ¡así que no te detengas! ¡Sigue disfrutando de tu Santal favorito!',
        ]);
    }
}
