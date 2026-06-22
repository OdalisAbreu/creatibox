<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'admin_primary_color',
        'admin_secondary_color',
        'form_primary_color',
        'form_secondary_color',
        'form_background_color',
        'form_instructions',
        'form_example_image',
    ];
}
