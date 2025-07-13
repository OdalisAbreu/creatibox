<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use App\Models\CaptureImage;
use App\Services\WasapiService;
use Illuminate\Http\Request;

class CaptureController extends Controller
{

    public function store(Request $request, $cell_phone)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'last_name' => 'nullable|string|max:255',
        //     'invoice_number' => 'required|string|max:255',
        //     'contact_number' => 'nullable|string|max:255',
        //     'city' => 'nullable|string|max:255',
        //     'storage' => 'nullable|string|max:255',
        //     'card_id' => 'required|string|max:255'
        // ]);

        $capture = Capture::where('cell_phone', $cell_phone)->first();

        if ($capture) {
            abort(409, 'Duplicate capture');
        }

        // limpiar el texto del $request->card_id para que solo contenga números y no contenga espacios
        $card_id = preg_replace('/\s+/', '', $request->card_id);
        $card_id = preg_replace('/\D/', '', $card_id);
        // verificar que el $request->card_id contenga solo números


        //    $path = $request->file('invoice_image')->store("public/invoices/");

        Capture::create([
            'cell_phone' => $cell_phone,
            'name' => $request->name,
            'last_name' => $request->last_name ?? '',
            'invoice_number' => $request->invoice_number,
            'contact_number' => $request->contact_number ?? $cell_phone,
            'city' => $request->city ?? '',
            'storage' => $request->storage ?? '',
            'card_id' => $card_id,
            'completed' => false
        ]);
    }

    public function showForm($cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail()->load('images');
        
        // Si ya tiene una imagen, redirigir a la página de completado
        if ($capture->images()->exists()) {
            return view('capture.completed', compact('capture'));
        }

        return view('capture.form', compact('capture'));
    }

    public function submitImage(Request $request, $cell_phone)
    {
        try {
            // Validar que se envíe una imagen
            $request->validate([
                'invoice_image' => 'required|image|max:3072' // 3MB máximo
            ]);

            $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail();

            // Verificar si ya tiene una imagen
            if ($capture->images()->exists()) {
                return redirect()->back()->with('error', 'Ya tienes una factura registrada.');
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

            $wasapiService = new WasapiService();
            $wasapiService->sendText($capture->cell_phone, "¡Tu registro fue completado de manera exitosa!  🥳🥳🥳\n\nYa estas participando🎉");

            return view('capture.completed', compact('capture'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
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
