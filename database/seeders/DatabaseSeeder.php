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

        User::factory()->create([
            'name' => 'Joel Medrano',
            'email' => 'joel@creatibox.com.do',
            'password' => '$2y$12$MTzjYQ2qi.ctlsNjRqspIengK/AQNvI5fEWS8EhUDwkLvJt6DwuYa', // password
        ]);
        User::factory()->create([
            'name' => 'Stephanie',
            'email' => 'stephanie@creatibox.com.do',
            'password' => '$2y$12$vExqygw/0aihd8njy2SrtuzCJijr3oB2QZKJPFJ7lQkfBz9eD43Sm', // password
        ]);

        //add wasapi account
        WasapiAccount::create([
            'phone' => '18099901415',
            'token' => '65853|X7le98KtKB5nKCj2ZavGgDrwAYjVIrK34uC6oI2f',
            'wasapi_id' => '13701',
            'final_message' => '¡Listo! Tu factura ha sido recibida correctamente. ✅

*¡Gracias por participar en Estrellas de la Navidad!* 🌟

Recuerda: mientras más facturas registres, más oportunidades tienes de ganar ese bar soñado para tu hogar. Promoción válida hasta el 10 de enero del 2026. Evite el exceso de alcohol. 

¡Mucha suerte! 🍀
',
        ]);
    }
}
