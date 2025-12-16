<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capture extends Model
{
    use HasFactory;
    protected $fillable = [
        'Code',
        'Description',
        'department',
        'sucursal',
        'collaborator',
        'completed',
    ];
    public function images()
    {
        return $this->hasMany(CaptureImage::class);
    }
}
