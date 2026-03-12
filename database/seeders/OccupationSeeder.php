<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    protected array $names = [
        'Aquitectura',
        'Diseño de Interiores',
        'Diseño Gráfico',
        'Moda',
        'Fotografía',
        'Cine / Audiovisual',
        'Arte / Artista Plástico',
        'Música / DJ / Producción Musical',
        'Periodista / Prensa',
        'Creador de Contenido/ Influencers',
        'Publicidad / Analista / Marketing',
        'Evento',
        'Sector Financiero / Banca',
        'Chef / Gastronomía',
        'Bares y Restaurantes / Mixología',
        'Belleza / Estética',
        'Fitness / Wellness',
        'Dermatología',
        'Medicina',
        'Retail Lujo',
        'Sector Público',
        'Bienes Raíces',
        'Estudiante',
        'Deportista',
    ];

    public function run(): void
    {
        foreach ($this->names as $name) {
            Occupation::firstOrCreate(['name' => $name]);
        }
    }
}

