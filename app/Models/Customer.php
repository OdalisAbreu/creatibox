<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'age_range',
        'gender',
        'instagram',
        'tiktok',
        'occupation',
        'occupation_other',
        'country',
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'customer_interest');
    }

    public const AGE_RANGES = [
        '18-24' => '18 a 24 años',
        '25-34' => '25 a 34 años',
        '35-44' => '35 a 44 años',
        '45-54' => '45 a 54 años',
        '55-64' => '55 a 64 años',
        '65+'   => '65 años o más',
    ];

    public const GENDERS = [
        'F' => 'Femenino',
        'M' => 'Masculino',
    ];

    public const OCCUPATIONS = [
        'Arquitectura',
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
        'Otro',
    ];
}
