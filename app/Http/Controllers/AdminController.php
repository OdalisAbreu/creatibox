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
        if ($request->filled('code')) {
            $query->where('captures.Code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('description')) {
            $query->where('captures.Description', 'like', '%' . $request->description . '%');
        }

        if ($request->filled('department')) {
            $query->where('captures.department', 'like', '%' . $request->department . '%');
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
        if (!$request->filled('code') && !$request->filled('description') && !$request->filled('department') && !$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status')) {
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
                'captures.Code',
                'captures.Description',
                'captures.department',
                'captures.sucursal',
                'captures.collaborator',
                'captures.completed',
                'captures.created_at',
                'capture_images.image_path',
                DB::raw("CASE WHEN captures.completed = 1 THEN 'Completo' ELSE 'Pendiente' END AS estado")
            );

        // Aplicar filtros
        if ($request->filled('code')) {
            $query->where('captures.Code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('description')) {
            $query->where('captures.Description', 'like', '%' . $request->description . '%');
        }

        if ($request->filled('department')) {
            $query->where('captures.department', 'like', '%' . $request->department . '%');
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
        if (!$request->filled('code') && !$request->filled('description') && !$request->filled('department') && !$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status')) {
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
                'captures.Code',
                'captures.Description',
                'captures.department',
                'captures.sucursal',
                'captures.collaborator',
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

        // Buscar todas las imágenes existentes para este capture
        $existingImages = CaptureImage::where('capture_id', $capture->id)->get();

        // Eliminar todas las imágenes anteriores del almacenamiento
        foreach ($existingImages as $existingImage) {
            if ($existingImage->image_path && Storage::disk('public')->exists($existingImage->image_path)) {
                Storage::disk('public')->delete($existingImage->image_path);
            }
        }

        // Eliminar todos los registros anteriores de la base de datos
        CaptureImage::where('capture_id', $capture->id)->delete();

        // Guardar la nueva imagen en el almacenamiento con el nombre del código
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = $capture->Code . '.' . $extension;
        $path = $file->storeAs('invoices', $fileName, 'public');

        // Crear un nuevo registro con la nueva imagen
        CaptureImage::create([
            'capture_id' => $capture->id,
            'image_path' => $path
        ]);

        // Actualizar el estado de la captura a completado
        $capture->update(['completed' => true]);

        return redirect()->route('dashboard')->with('success', 'Imagen subida correctamente.');
    }

    public function storeCapture(Request $request)
    {
        try {
            // Validar campos requeridos incluyendo que Code sea único
            $request->validate([
                'Code' => 'required|string|unique:captures,Code',
                'Description' => 'required|string',
                'department' => 'required|string',
                'sucursal' => 'required|string',
                'collaborator' => 'required|string',
                'invoice_image' => 'required|image|max:5072'
            ], [
                'Code.required' => 'El campo código es obligatorio.',
                'Code.unique' => 'Este código ya está registrado. Por favor, use un código diferente.',
                'Description.required' => 'El campo descripción es obligatorio.',
                'department.required' => 'El campo departamento es obligatorio.',
                'sucursal.required' => 'El campo sucursal es obligatorio.',
                'collaborator.required' => 'El campo colaborador es obligatorio.',
                'invoice_image.required' => 'La imagen es obligatoria.',
                'invoice_image.image' => 'El archivo debe ser una imagen.',
                'invoice_image.max' => 'La imagen no debe ser mayor a 5MB.',
            ]);

            // Crear el registro de captura
            $capture = Capture::create([
                'Code' => $request->Code,
                'Description' => $request->Description,
                'department' => $request->department,
                'sucursal' => $request->sucursal,
                'collaborator' => $request->collaborator,
                'completed' => true,
            ]);

            // Guardar la imagen en el almacenamiento con el nombre del código
            $file = $request->file('invoice_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->Code . '.' . $extension;
            $path = $file->storeAs('invoices', $fileName, 'public');

            // Crear el registro de la imagen
            CaptureImage::create([
                'capture_id' => $capture->id,
                'image_path' => $path
            ]);

            return redirect()->route('dashboard')->with('success', 'Registro creado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback por si la validación no captura el error de unique
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['Code' => 'Este código ya está registrado. Por favor, use un código diferente.']);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al crear registro: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Ocurrió un error al crear el registro. Por favor, inténtelo de nuevo.']);
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
        if (!$request->filled('Code')) {
            return response()->json([
                'success' => false,
                'message' => 'El campo código es requerido.'
            ], 422);
        }
        
        // Validar que el código no esté duplicado (excluyendo el registro actual)
        $existingCapture = Capture::where('Code', $request->Code)
            ->where('id', '!=', $id)
            ->first();
            
        if ($existingCapture) {
            return response()->json([
                'success' => false,
                'message' => 'Este código ya está registrado.'
            ], 422);
        }

        // Preparar datos para actualizar
        $updateData = [
            'Code' => $request->Code,
            'Description' => $request->Description ?? '',
            'department' => $request->department ?? '',
            'sucursal' => $request->sucursal ?? '',
            'collaborator' => $request->collaborator ?? '',
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
