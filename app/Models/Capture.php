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
       // 'gender',
        'city',
        'storage',
      //  'invoice_number',
        'contact_number',
        'card_id',
        'completed',
        'number_send_message',
        'passport',
    ];
    public function images()
    {
        return $this->hasMany(CaptureImage::class);
    }
}
