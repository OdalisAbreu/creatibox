<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    protected array $names = [
        'Arte',
        'Cine',
        'Teatro',
        'Literatura',
        'Música',
        'Gastronomía',
        'Coctelería',
        'Vinos',
        'Bebidas sin alcohol',
        'Viajes',
        'Turismo',
        'Moda',
        'Joyería y relojería',
        'Automóviles',
        'Arquitectura',
        'Diseño de Interiores',
        'Belleza',
        'Skincare',
        'Maquillaje',
        'Wellness',
        'Gaming',
        'Tecnología',
        'Deportes',
        'Construcción',
        'Política',
        'Cultura',
    ];

    public function run(): void
    {
        foreach ($this->names as $name) {
            Interest::firstOrCreate(['name' => $name]);
        }
    }
}
