<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use Illuminate\Http\Request;

class CaptureController extends Controller
{
    public function store(Request $request, $cell_phone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:0',
            'card_id' => 'required|string|max:255'
        ]);

        $capture = Capture::where('cell_phone', $cell_phone)->first();

        if ($capture) {
            abort(409, 'Duplicate capture');
        }

        $path = $request->file('invoice_image')->store("public/invoices/");

        Capture::create([
            'cell_phone' => $cell_phone,
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'age' => $request->age,
            'card_id' => $request->card_id,
            'image_path' => $path,
            'completed' => true,
        ]);
    }

    public function showForm($cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail();

        if ($capture->completed) {
            return view('capture.completed');
        }

        return view('capture.form', compact('capture'));
    }

    public function submitImage(Request $request, $cell_phone)
    {
        $capture = Capture::where('cell_phone', $cell_phone)->firstOrFail();

        if ($capture->completed) {
            return redirect()->back()->with('error', 'Ya has enviado tu factura.');
        }

        $request->validate([
            'invoice_image' => 'required|image|max:3072',
        ]);

        $path = $request->file('invoice_image')->store("public/invoices/");

        $capture->update([
            'image_path' => $path,
            'completed' => true,
        ]);

        return redirect()->route('capture.success');
    }
}
