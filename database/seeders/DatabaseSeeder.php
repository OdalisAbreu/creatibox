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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // password
        ]);


        //add wasapi account
        WasapiAccount::create([
            'phone' => '18299387179',
            'token' => '339512|rZkXM1LIG2GlgZmKZ5EMTK32HHMuOJTrvEKGB0Tgcebcd968',
            'wasapi_id' => '20349',
            'final_message' => '¡Listo! Tu factura ha sido recibida correctamente. ✅

*

¡Gracias por participar en Arma tu Combo y Gana!

Recuerda: Mientras más compres, ¡más chances tienes de ganar!

Promoción válida hasta el 3 de abril 2026.

¡Mucha suerte!

¡Mucha suerte! 🍀
',
        ]);
    }
}
