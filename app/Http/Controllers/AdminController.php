<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CapturesExport;
use App\Models\Capture;
use App\Models\CaptureImage;

use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as MPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('captures.completed', 1);
            } elseif ($request->status === 'pending') {
                $query->where('captures.completed', 0);
            }
        }

        // Si no hay filtros, traer los registros de los últimos 3 días
        if (!$request->filled('name') && !$request->filled('cell_phone') && !$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status')) {
            $query->whereDate('captures.created_at', '>=', now()->subDays(3));
        }

        // Totales
        $pendingCount = Capture::where('completed', 0)->count();
        $completedCount = CaptureImage::count(); // Contar imágenes como completados
        $totalCount = $pendingCount + $completedCount;

        $captures = $query->paginate(15);

        return view('admin.index', compact('captures', 'pendingCount', 'completedCount', 'totalCount'));
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
                'captures.id',
                'captures.invoice_number',
                'captures.name',
                'captures.last_name',
                'captures.card_id',
                'captures.cell_phone',
                'captures.contact_number',
                'captures.city',
                'captures.storage',
                'captures.completed',
                'captures.created_at',
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

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('captures.completed', 1);
            } elseif ($request->status === 'pending') {
                $query->where('captures.completed', 0);
            }
        }

        // Si no hay filtros, traer los registros de los últimos 3 días
        if (!$request->filled('name') && !$request->filled('cell_phone') && !$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status')) {
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
        //valida si el usuario no tiene imagenes y si no las tiene cambia el estado a 0
        $capture = Capture::find($captureImage->capture_id);
        if ($capture->images()->count() == 0) {
            $capture->update(['completed' => false]);
        }
        // Eliminar la imagen del almacenamiento

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
                'captures.last_name',
                'captures.contact_number',
                'captures.city',
                'captures.storage',
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

    public function uploadImage(Request $request)
    {
        $capture = Capture::findOrFail($request->capture_id);

        // Guardar la imagen en el almacenamiento
        $path = $request->file('image')->store('invoices', 'public');

        // Crear o actualizar el registro de la imagen
        CaptureImage::updateOrCreate(
            ['capture_id' => $capture->id],
            ['image_path' => $path]
        );
        // Actualizar el estado de la captura a completado
        $capture->update(['completed' => true]);

        return redirect()->route('dashboard')->with('success', 'Imagen subida correctamente.');
    }

    public function storeCapture(Request $request)
    {
        try {
         /*   $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'gender' => 'required|in:male,female,other',
                'age' => 'required|integer|min:0',
                'card_id' => 'required|string|max:255',
                'cell_phone' => 'required|string|max:255|unique:captures,cell_phone',
                'invoice_image' => 'required|image|max:2048'
            ]);*/

            // Limpiar el card_id para que solo contenga números
            $card_id = preg_replace('/\s+/', '', $request->card_id);
            $card_id = preg_replace('/\D/', '', $card_id);

            // Crear el registro de captura
            $capture = Capture::create([
                'cell_phone' => $request->cell_phone,
                'name' => $request->name,
                'last_name' => $request->last_name ?? '',
                'invoice_number' => $request->invoice_number,
                'contact_number' => $request->contact_number ?? $request->cell_phone,
                'city' => $request->city ?? '',
                'storage' => $request->storage ?? '',
                'card_id' => $card_id,
                'completed' => true
            ]);

            // Guardar la imagen en el almacenamiento
            $path = $request->file('invoice_image')->store('invoices', 'public');

            // Crear el registro de la imagen
            CaptureImage::create([
                'capture_id' => $capture->id,
                'image_path' => $path
            ]);

                    return redirect()->route('dashboard')->with('success', 'Participante creado correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cell_phone' => 'Este número de celular ya está registrado.']);
        }
        throw $e;
    }
}

public function editCapture($id)
{
    $capture = Capture::findOrFail($id);
    return response()->json($capture);
}

public function updateCapture(Request $request, $id)
{
    Log::info('Método updateCapture llamado con ID:', ['id' => $id]);
    
    try {
        $capture = Capture::findOrFail($id);
        
        // Validar campos requeridos
        if (!$request->filled('cell_phone')) {
            return response()->json([
                'success' => false,
                'message' => 'El campo celular es requerido.'
            ], 422);
        }
        
        // Validar que el celular no esté duplicado (excluyendo el registro actual)
        $existingCapture = Capture::where('cell_phone', $request->cell_phone)
            ->where('id', '!=', $id)
            ->first();
            
        if ($existingCapture) {
            return response()->json([
                'success' => false,
                'message' => 'Este número de celular ya está registrado.'
            ], 422);
        }

        // Limpiar el card_id para que solo contenga números
        $card_id = $request->filled('card_id') ? preg_replace('/\s+/', '', $request->card_id) : '';
        $card_id = preg_replace('/\D/', '', $card_id);

        // Preparar datos para actualizar
        $updateData = [
            'name' => $request->name ?? '',
            'last_name' => $request->last_name ?? '',
            'invoice_number' => $request->invoice_number ?? '',
            'cell_phone' => $request->cell_phone,
            'contact_number' => $request->contact_number ?? '',
            'city' => $request->city ?? '',
            'storage' => $request->storage ?? '',
            'card_id' => $card_id,
        ];
        
        // Log para debug
        Log::info('Datos a actualizar:', $updateData);

        $capture->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Registro actualizado correctamente.'
        ]);
    } catch (\Exception $e) {
        Log::error('Error en updateCapture:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el registro: ' . $e->getMessage()
        ], 500);
    }
}
}
