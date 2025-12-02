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

    public function store(Request $request, $cell_phone)
    {

        $capture = Capture::where('cell_phone', $cell_phone)->first();
    
        if ($capture) {
            $phone = $request->contact_number ?? $cell_phone;
        }

        // limpiar el texto del $request->card_id para que solo contenga números y no contenga espacios
        $card_id = preg_replace('/\s+/', '', $request->card_id);
        $card_id = preg_replace('/\D/', '', $card_id);


    
        Capture::create([
            'cell_phone' => $phone ?? $cell_phone,
            'name' => $request->name,
       //     'invoice_number' => $request->invoice_number,
            'contact_number' => $request->contact_number ?? $cell_phone,
            'city' => $request->city ?? '',
            'storage' => $request->storage ?? '',
            'card_id' => $card_id,
            'completed' => false,
            'number_send_message' => $cell_phone,
            'passport' => $request->passport ?? '',
        ]);
    }

    public function showForm($cell_phone)
    {
        try {
            $capture = Capture::where('cell_phone', $cell_phone)->latest()->first();
            
            if (!$capture) {
                Log::warning('Intento de acceso a formulario con número no registrado: ' . $cell_phone);
                // Mostrar una vista de error en lugar de redirigir
                return view('capture.error', [
                    'message' => 'No se encontró el cliente con el número proporcionado.',
                    'cell_phone' => $cell_phone
                ]);
            }
            
            $capture->load('images');
        } catch (\Exception $e) {
            Log::error('Error al mostrar el formulario: ' . $e->getMessage(), [
                'cell_phone' => $cell_phone,
                'trace' => $e->getTraceAsString()
            ]);
            return view('capture.error', [
                'message' => 'Ocurrió un error al cargar el formulario. Por favor, intenta nuevamente.',
                'cell_phone' => $cell_phone
            ]);
        }
        
        // Si ya tiene una imagen, redirigir a la página de completado
        $wasapiAccount = WasapiAccount::first();
        return view('capture.form', compact('capture', 'wasapiAccount'));
    }

    public function submitImage(Request $request, $cell_phone)
    {
        Log::info('submitImage', ['request' => $request->all()]);
        try {
            // Validar que se envíe una imagen
            $request->validate([
                'invoice_image' => 'required|image|max:5072' // 3MB máximo
            ]);

            $capture = Capture::where('cell_phone', $cell_phone)->latest()->first();
            
            if (!$capture) {
                Log::warning('Intento de subir imagen con número no registrado: ' . $cell_phone);
                return redirect()->back()->with('error', 'No se encontró el cliente. Por favor, verifica el número de teléfono.');
            }


            // Guarda en storage/app/public/invoices y retorna el path relativo
            $path = $request->file('invoice_image')->store("invoices", 'public');

            // Guarda el path accesible públicamente con Storage::url()
            CaptureImage::create([
                'capture_id' => $capture->id,
                'image_path' => $path,
            ]);
            
            $capture->update([
                'completed' => true,
            ]);
            
            $wasapiAccount = WasapiAccount::first();
          $mensaje = $wasapiAccount->final_message;
//return $wasapiAccount->final_message;
            $wasapiService = new WasapiService();
            $wasapiService->sendText($capture->number_send_message ?? $capture->cell_phone, $mensaje);
            return view('capture.completed', compact('capture', 'wasapiAccount'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
          Log::error('Error al procesar la imagen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la imagen. Por favor, intenta nuevamente.');
        } 
    }


    public function getClient($cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->first();

        if (!$capture) {
            return response()->json(['message' => 'Capture not found'], 404);
        }

        return response()->json($capture->load('images'));
    }
}
