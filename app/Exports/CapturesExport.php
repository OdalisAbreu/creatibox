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
                'captures.Code',
                'captures.Description',
                'captures.department',
                'captures.sucursal',
                'captures.collaborator',
                DB::raw("CONCAT('" . url('storage') . "/', capture_images.image_path) AS full_image_path"),
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS completed_status"),
                DB::raw("DATE_FORMAT(captures.created_at, '%d/%m/%Y') AS formatted_created_at")
            );

        // Aplicar filtros
        if (!empty($this->filters['code'])) {
            $query->where('captures.Code', 'like', '%' . $this->filters['code'] . '%');
        }

        if (!empty($this->filters['description'])) {
            $query->where('captures.Description', 'like', '%' . $this->filters['description'] . '%');
        }

        if (!empty($this->filters['department'])) {
            $query->where('captures.department', 'like', '%' . $this->filters['department'] . '%');
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('captures.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('captures.created_at', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['status'])) {
            if ($this->filters['status'] === 'completed') {
                $query->where('captures.completed', 1);
            } elseif ($this->filters['status'] === 'pending') {
                $query->where('captures.completed', 0);
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID', 
            'Código', 
            'Descripción', 
            'Departamento', 
            'Sucursal', 
            'Colaborador', 
            'Factura', 
            'Estado', 
            'Fecha Registro'
        ];
    }
}
