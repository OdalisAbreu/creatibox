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
        'occupation_other',
        'country',
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'customer_interest');
    }

    public function occupations()
    {
        return $this->belongsToMany(Occupation::class, 'customer_occupation');
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

}
