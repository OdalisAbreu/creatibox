<?php

namespace App\Services;

use App\Models\WasapiAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WasapiService
{
    protected string $baseUrl;
    protected string $token;
    protected int $fromId;

    public function __construct()
    {
        $wasapiAccount = WasapiAccount::first();
        $this->baseUrl = config('services.wasapi.url');
        $this->token   = $wasapiAccount->token;
        $this->fromId  = $wasapiAccount->wasapi_id;
    }

    /**
     * Envía un mensaje de texto por WhatsApp.
     *
     * @param  string  $to    Número destino en formato internacional (ej. "18294428902")
     * @param  string  $text  Contenido del mensaje
     * @return array          Respuesta decodificada en array
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function sendText(string $waId, string $message): array
    {
        try {
        $endpoint = "{$this->baseUrl}/whatsapp-messages";

        $payload = [
            'message' => $message,
            'wa_id'   => $waId,
            'from_id' => $this->fromId,
        ];

        $response = Http::withToken($this->token)
            ->contentType('application/json')
            ->acceptJson()
            ->post($endpoint, $payload)
            ->throw();

        return $response->json();
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());
            return [];
        }
    }
}
