<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use App\Models\CaptureImage;
use App\Models\WasapiAccount;
use App\Services\WasapiService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'contact_number' => $request->contact_number ?? $cell_phone,
            'city' => $request->city ?? '',
            'storage' => $request->storage ?? '',
            'card_id' => $card_id,
            'completed' => false,
            'number_send_message' => $cell_phone,
        ]);
    }

    public function showForm($cell_phone)
    {
        try {
            $capture = Capture::where('cell_phone', $cell_phone)->latest()->first();
            Log::info('capture', ['capture' => $capture]);
            
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
                'invoice_image' => 'required|image|max:10240' // 10MB máximo
            ]);

            $capture = Capture::where('cell_phone', $cell_phone)->latest()->first();
            
            if (!$capture) {
                Log::warning('Intento de subir imagen con número no registrado: ' . $cell_phone);
                return redirect()->back()->with('error', 'No se encontró el cliente. Por favor, verifica el número de teléfono.');
            }


            // Guardar comprimida (JPEG) cuando GD lo permita; si no, archivo original
            $path = $this->storeCompressedInvoiceImage($request->file('invoice_image'));

            // Guarda el path accesible públicamente con Storage::url()
            CaptureImage::create([
                'capture_id' => $capture->id,
                'image_path' => $path,
            ]);
            
            $capture->update([
                'completed' => true,
            ]);
            
            $wasapiAccount = WasapiAccount::first();
          $mensaje = 'Tu factura ha sido recibida correctamente ' . $capture->name . ' ' . $wasapiAccount->final_message;
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

    /**
     * Comprime y redimensiona la factura (fotos grandes) y la guarda como JPEG.
     * Si GD no está disponible o el formato no es compatible, devuelve el store() original.
     */
    private function storeCompressedInvoiceImage(UploadedFile $file): string
    {
        if (! extension_loaded('gd')) {
            Log::warning('GD no disponible: subiendo imagen sin compresión');

            return $file->store('invoices', 'public');
        }

        $mime = $file->getMimeType() ?: '';
        // Vectores: sin reprocesar con GD
        if (Str::contains($mime, 'svg')) {
            return $file->store('invoices', 'public');
        }

        $realPath = $file->getRealPath();
        if (! $realPath || ! is_readable($realPath)) {
            return $file->store('invoices', 'public');
        }

        try {
            $src = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($realPath),
                'image/png' => @imagecreatefrompng($realPath),
                'image/gif' => @imagecreatefromgif($realPath),
                'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($realPath) : false,
                default => false,
            };

            if (! $src) {
                return $file->store('invoices', 'public');
            }

            $w = imagesx($src);
            $h = imagesy($src);
            if ($w < 1 || $h < 1) {
                unset($src);

                return $file->store('invoices', 'public');
            }

            $maxDimension = (int) config('capture.invoice_max_dimension', 2560);
            $jpegQuality = (int) config('capture.invoice_jpeg_quality', 82);

            if ($w > $maxDimension || $h > $maxDimension) {
                $ratio = min($maxDimension / $w, $maxDimension / $h);
                $nw = max(1, (int) round($w * $ratio));
                $nh = max(1, (int) round($h * $ratio));
                $dst = imagecreatetruecolor($nw, $nh);
                if ($mime === 'image/png' || $mime === 'image/gif') {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
                unset($src);
                $src = $dst;
                $w = $nw;
                $h = $nh;
            }

            // JPEG sin canal alpha: fondo blanco para PNG/GIF
            if (in_array($mime, ['image/png', 'image/gif'], true)) {
                $flat = imagecreatetruecolor($w, $h);
                $white = imagecolorallocate($flat, 255, 255, 255);
                imagefill($flat, 0, 0, $white);
                imagecopy($flat, $src, 0, 0, 0, 0, $w, $h);
                unset($src);
                $src = $flat;
            }

            $relative = 'invoices/'.uniqid('inv_', true).'.jpg';
            $fullPath = storage_path('app/public/'.$relative);

            $dir = dirname($fullPath);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if (! imagejpeg($src, $fullPath, max(40, min(95, $jpegQuality)))) {
                unset($src);

                return $file->store('invoices', 'public');
            }

            unset($src);

            return $relative;
        } catch (\Throwable $e) {
            Log::warning('Fallo al comprimir imagen de factura, se guarda original', [
                'message' => $e->getMessage(),
            ]);

            return $file->store('invoices', 'public');
        }
    }
}
