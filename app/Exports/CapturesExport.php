<?php

namespace App\Exports;

use App\Models\Capture;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CapturesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Capture::select('id', 'user_id', 'cell_phone', 'completed', 'image_path', 'created_at', 'updated_at')->get();
    }

    public function headings(): array
    {
        return ['ID', 'User ID', 'Cell Phone', 'Completed', 'Image Path', 'Created At', 'Updated At'];
    }
}
