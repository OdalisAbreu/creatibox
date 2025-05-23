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
        //     'email' => 'required|email',
        //     'gender' => 'required|in:male,female,other',
        //     'age' => 'required|integer|min:0',
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
            'email' => $request->email,
            'gender' => $request->gender,
            'age' => $request->age,
            'card_id' => $card_id
        ]);
    }

    public function showForm($cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail()->load('images');
        // if ($capture->completed) {
        //     return view('capture.completed');
        // }

        return view('capture.form', compact('capture'));
    }

    public function submitImage(Request $request, $cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail();

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
