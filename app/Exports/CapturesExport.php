<?php

namespace App\Exports;

use App\Models\Capture;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CapturesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 15,  // Código
            'C' => 30,  // Descripción
            'D' => 25,  // Departamento
            'E' => 15,  // Sucursal
            'F' => 25,  // Colaborador
            'G' => 40,  // Factura
            'H' => 15,  // Estado
            'I' => 15,  // Fecha Registro
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado (fila 1)
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'C41230'], // Rojo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Estilo para las filas de datos (alternando colores)
        $highestRow = $sheet->getHighestRow();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F5F5F5'; // Blanco y gris claro alternado
            
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '425968'], // Gris azulado para bordes
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        // Altura de fila para el encabezado
        $sheet->getRowDimension(1)->setRowHeight(25);

        return $sheet;
    }
}
