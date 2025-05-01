<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CapturesExport;
use App\Exports\CapturesPdfExport;
use App\Models\Capture;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        // trae todos los captures de la base de datos paginados
        $captures = Capture::paginate(15);
        return view('admin.index', compact('captures'));
    }

    public function exportExcel()
    {
        return Excel::download(new CapturesExport, 'captures.xlsx');
    }

    public function exportPdf()
    {
        $captures = Capture::with('user')->get();
        $pdf = Pdf::loadView('admin.export-pdf', compact('captures'));

        return $pdf->download('captures.pdf');
    }
}
