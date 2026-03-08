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
        'card_id',
        'city',
        'storage',
        'contact_number',
        'completed',
        'number_send_message',
    ];
    public function images()
    {
        return $this->hasMany(CaptureImage::class);
    }
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
