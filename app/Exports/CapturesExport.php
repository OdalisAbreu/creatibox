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
        $storageUrl = addslashes(url('storage') . '/');
        $query = Capture::select(
            'captures.id',
            'captures.name',
            'captures.card_id',
            'captures.cell_phone',
            'captures.contact_number',
            'captures.city',
            'captures.storage',
            DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS completed_status"),
            DB::raw("(SELECT GROUP_CONCAT(CONCAT('" . $storageUrl . "', image_path) ORDER BY id SEPARATOR '; ') FROM capture_images WHERE capture_images.capture_id = captures.id) AS full_image_path"),
            DB::raw("DATE_FORMAT(captures.created_at, '%d/%m/%Y') AS formatted_created_at"),
            'captures.rejected_reason',
            'captures.comment',
            DB::raw("(SELECT COALESCE(SUM(CAST(ticket_number AS UNSIGNED)), 0) FROM tikets WHERE tikets.capture_id = captures.id) AS total_boletos")
        );

        // Aplicar filtros
        if (!empty($this->filters['name'])) {
            $query->where('captures.name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (!empty($this->filters['cell_phone'])) {
            $query->where('captures.cell_phone', 'like', '%' . $this->filters['cell_phone'] . '%');
        }

        if (!empty($this->filters['card_id'])) {
            $query->where('captures.card_id', 'like', '%' . $this->filters['card_id'] . '%');
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
        return [
            'ID',
            'Nombre',
            'Cédula',
            'Celular',
            'Número de Contacto',
            'Ciudad',
            'Almacén',
            'Estado',
            'Factura',
            'Fecha Registro',
            'Motivo Rechazo',
            'Comentario',
            'Total de boletos'
        ];
    }
}
