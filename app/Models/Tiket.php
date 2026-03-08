<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'capture_id',
        'ticket_number',
    ];  
    public function capture()
    {
        return $this->belongsTo(Capture::class);
    }
}
