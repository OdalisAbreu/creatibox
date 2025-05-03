<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptureImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'capture_id',
        'image_path',
    ];
    public function capture()
    {
        return $this->belongsTo(Capture::class);
    }
}
