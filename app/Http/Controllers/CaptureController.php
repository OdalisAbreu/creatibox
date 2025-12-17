<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use App\Models\CaptureImage;
use App\Models\WasapiAccount;
use App\Services\WasapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaptureController extends Controller
{

    public function store(Request $request, $code)
    {
        $capture = Capture::where('Code', $code)->first();
    
        if ($capture) {
            // Si ya existe, actualizar
            $capture->update([
                'Description' => $request->Description ?? $capture->Description,
                'department' => $request->department ?? $capture->department,
                'sucursal' => $request->sucursal ?? $capture->sucursal,
                'collaborator' => $request->collaborator ?? $capture->collaborator,
            ]);
        } else {
            // Crear nuevo registro
            Capture::create([
                'Code' => $code,
                'Description' => $request->Description ?? '',
                'department' => $request->department ?? '',
                'sucursal' => $request->sucursal ?? null,
                'collaborator' => $request->collaborator ?? null,
            ]);
        }
    }

    public function showForm($code)
    {
        try {
            $capture = Capture::where('Code', $code)->latest()->first();
            
            if (!$capture) {
                Log::warning('Intento de acceso a formulario con código no registrado: ' . $code);
                // Mostrar una vista de error en lugar de redirigir
                return view('capture.error', [
                    'message' => 'No se encontró el registro con el código proporcionado.',
                    'code' => $code
                ]);
            }
            
            $capture->load('images');
        } catch (\Exception $e) {
            Log::error('Error al mostrar el formulario: ' . $e->getMessage(), [
                'code' => $code,
                'trace' => $e->getTraceAsString()
            ]);
            return view('capture.error', [
                'message' => 'Ocurrió un error al cargar el formulario. Por favor, intenta nuevamente.',
                'code' => $code
            ]);
        }
        
        $wasapiAccount = WasapiAccount::first();
        return view('capture.form', compact('capture', 'wasapiAccount'));
    }

    public function submitImage(Request $request, $code)
    {
        Log::info('submitImage', ['request' => $request->all()]);
        try {
            // Validar que se envíe una imagen
            $request->validate([
                'invoice_image' => 'required|image|max:5072' // 3MB máximo
            ]);

            $capture = Capture::where('Code', $code)->latest()->first();
            
            if (!$capture) {
                Log::warning('Intento de subir imagen con código no registrado: ' . $code);
                return redirect()->back()->with('error', 'No se encontró el registro. Por favor, verifica el código.');
            }

            // Guarda en storage/app/public/invoices con el nombre del código
            $file = $request->file('invoice_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $capture->Code . '.' . $extension;
            $path = $file->storeAs('invoices', $fileName, 'public');

            // Guarda el path accesible públicamente con Storage::url()
            CaptureImage::create([
                'capture_id' => $capture->id,
                'image_path' => $path,
            ]);
            
            $wasapiAccount = WasapiAccount::first();
            $mensaje = $wasapiAccount->final_message;
            
            // Solo enviar mensaje si hay un número disponible en el colaborador o código
            // Nota: El modelo actual no tiene cell_phone, así que se omite el envío de WhatsApp
            // Si necesitas enviar mensajes, deberás agregar un campo para el número de teléfono
            
            return view('capture.completed', compact('capture', 'wasapiAccount'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error al procesar la imagen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la imagen. Por favor, intenta nuevamente.');
        } 
    }


    public function getClient($code)
    {
        $capture = Capture::where('Code', $code)->first();

        if (!$capture) {
            return response()->json(['message' => 'Capture not found'], 404);
        }

        return response()->json($capture->load('images'));
    }
}
