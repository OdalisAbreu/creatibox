<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Capture;
use App\Models\CaptureImage;

class CountRecords extends Command
{
    protected $signature = 'count:records';
    protected $description = 'Contar registros en las tablas';

    public function handle()
    {
        $capturesCount = Capture::count();
        $imagesCount = CaptureImage::count();
        
        $this->info("Total de capturas: {$capturesCount}");
        $this->info("Total de imágenes: {$imagesCount}");
        
        if ($capturesCount > 0) {
            $this->info("Primera captura:");
            $firstCapture = Capture::first();
            $this->table(['ID', 'Nombre', 'Celular', 'Creado'], [[
                $firstCapture->id,
                $firstCapture->name,
                $firstCapture->cell_phone,
                $firstCapture->created_at->format('d/m/Y H:i')
            ]]);
        }
        
        if ($imagesCount > 0) {
            $this->info("Primera imagen:");
            $firstImage = CaptureImage::first();
            $this->table(['ID', 'Capture ID', 'Image Path'], [[
                $firstImage->id,
                $firstImage->capture_id,
                $firstImage->image_path
            ]]);
        }
    }
} 