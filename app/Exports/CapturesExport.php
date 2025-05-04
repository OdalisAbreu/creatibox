<?php

namespace App\Exports;

use App\Models\Capture;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CapturesExport implements FromCollection, WithHeadings
{
    protected $filters = [];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
    public function collection()
    {
        $query = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
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
            );

        // Aplicar filtros
        if (!empty($this->filters['name'])) {
            $query->where('captures.name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (!empty($this->filters['cell_phone'])) {
            $query->where('captures.cell_phone', 'like', '%' . $this->filters['cell_phone'] . '%');
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('captures.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('captures.created_at', '<=', $this->filters['end_date']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Celular', 'Correo', 'Género', 'Edad', 'Cédula', 'Factura', 'Completo',  'Fecha Registro', 'Fecha Regostro Factura'];
    }
}
