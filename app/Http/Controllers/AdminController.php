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
        $captures = Capture::with('user')
            ->when($request->name, fn($q) => $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->name}%")))
            ->when($request->cell_phone, fn($q) => $q->where('cell_phone', 'like', "%{$request->cell_phone}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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
