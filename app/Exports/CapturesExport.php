<?php

namespace App\Exports;

use App\Models\Capture;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CapturesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
            ->select(
                'captures.id',
                'captures.name',
                'captures.cell_phone',
                'captures.email',
                'captures.gender',
                'captures.age',
                'captures.card_id',
                DB::raw("CONCAT('" . url('storage') . "/', capture_images.image_path) AS full_image_path"),
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS completed_status"),
                'captures.created_at',
                'capture_images.created_at AS invoice_created_at'
            )
            ->latest('captures.created_at')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Celular', 'Correo', 'Género', 'Edad', 'Cédula', 'Factura', 'Completo',  'Fecha Registro', 'Fecha Regostro Factura'];
    }
}
