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
            'final_message' => 'Ya estás a bordo para ganar u viaje todo pago al clásico mundial de béisbol! Gracias por registrarte en nuestra gran promoción.',
        ]);
    }
}
