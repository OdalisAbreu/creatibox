<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CapturesExport;
use App\Models\Capture;
use App\Models\CaptureImage;

use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as MPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
            ->select(
                'captures.*',
                'capture_images.image_path',
                'capture_images.id AS image_id',
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS estado")
            );

        // Aplicar filtros
        if ($request->filled('name')) {
            $query->where('captures.name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('cell_phone')) {
            $query->where('captures.cell_phone', 'like', '%' . $request->cell_phone . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('captures.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('captures.created_at', '<=', $request->end_date);
        }

        // Si no hay filtros, traer los registros de los últimos 3 días
        if (!$request->filled('name') && !$request->filled('cell_phone') && !$request->filled('start_date') && !$request->filled('end_date')) {
            $query->whereDate('captures.created_at', '>=', now()->subDays(3));
        }

        $captures = $query->latest('captures.created_at')->paginate(15);

        return view('admin.index', compact('captures'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();

        // Si no hay filtros, agregar el rango de los últimos 3 días
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $filters['start_date'] = now()->subDays(3)->toDateString();
            $filters['end_date'] = now()->toDateString();
        }

        return Excel::download(new CapturesExport($filters), 'captures.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
            ->select(
                'captures.*',
                'capture_images.image_path',
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS estado")
            );

        // Aplicar filtros
        if ($request->filled('name')) {
            $query->where('captures.name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('cell_phone')) {
            $query->where('captures.cell_phone', 'like', '%' . $request->cell_phone . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('captures.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('captures.created_at', '<=', $request->end_date);
        }

        // Si no hay filtros, traer los registros de los últimos 3 días
        if (!$request->filled('name') && !$request->filled('cell_phone') && !$request->filled('start_date') && !$request->filled('end_date')) {
            $query->whereDate('captures.created_at', '>=', now()->subDays(3));
        }

        $captures = $query->latest('captures.created_at')->get();

        $pdf = MPDF::loadView('admin.export-pdf', compact('captures'), [
            'title' => 'Listado de Capturas',
            'margin_top' => 8,
            'margin_left' => 6,
        ]);

        return $pdf->stream('captures.pdf');
    }

    public function deleteCapture($id)
    {
        $captureImage = CaptureImage::find($id);
        $captureImage->delete();

        return redirect()->route('dashboard')->with('success', 'Capture deleted successfully.');
    }

    public function previewPdf()
    {
        // Usa la MISMA consulta que exportPdf()
        $captures = Capture::leftJoin('capture_images', 'captures.id', '=', 'capture_images.capture_id')
            ->select(
                'captures.id',
                'captures.name',
                'captures.cell_phone',
                'captures.email',
                'captures.gender',
                'captures.age',
                'captures.card_id',
                'capture_images.image_path',
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS estado"),
                'captures.created_at',
                'capture_images.created_at AS invoice_created_at'
            )
            ->latest('captures.created_at')
            ->limit(50)            //  ≤50 para que cargue rápido en pantalla
            ->get();

        // Retorna el HTML plano (sin mPDF)
        return view('admin.export-pdf', compact('captures'));
    }
}
