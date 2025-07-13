<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capture extends Model
{
    use HasFactory;
    protected $fillable = [
        'cell_phone',
        'name',
        'last_name',
        'city',
        'storage',
        'invoice_number',
        'contact_number',
        'card_id',
        'completed',
    ];
    public function images()
    {
        return $this->hasMany(CaptureImage::class);
    }
}
