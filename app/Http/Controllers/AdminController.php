<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CapturesExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Capture;
use App\Models\CaptureImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $captures = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
            ->select('captures.*', 'capture_images.id AS image_id', 'capture_images.image_path')
            ->latest('captures.created_at')
            ->paginate(15);

        return view('admin.index', compact('captures'));
    }

    public function exportExcel()
    {
        return Excel::download(new CapturesExport, 'captures.xlsx');
    }

    public function exportPdf()
    {
        $captures = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
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

        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('admin.export-pdf', compact('captures'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('captures.pdf');
    }
    public function deleteCapture($id)
    {
        $captureImage = CaptureImage::find($id);
        $captureImage->delete();

        return redirect()->route('dashboard')->with('success', 'Capture deleted successfully.');
    }
}
