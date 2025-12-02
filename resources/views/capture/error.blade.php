{{-- resources/views/capture/error.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error – FacturaCapture</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: #f7f9fa;
            padding: 1rem 0;
        }

        .error-card {
            width: 90vw;
            max-width: 420px;
            margin: 2rem auto;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
            text-align: center;
        }

        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .error-card h1 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .error-card p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .error-card .cell-phone {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-family: monospace;
            color: #495057;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3">
        <div class="error-card">
            <i class="bi bi-exclamation-triangle-fill error-icon"></i>
            <h1>Cliente no encontrado</h1>
            <p>{{ $message ?? 'No se encontró el cliente con el número proporcionado.' }}</p>
            
            @if(isset($cell_phone))
            <div class="mb-3">
                <small class="text-muted">Número de teléfono:</small>
                <div class="cell-phone mt-2">{{ $cell_phone }}</div>
            </div>
            @endif

            <p class="text-muted small">
                Por favor, verifica que el enlace sea correcto o contacta con soporte si el problema persiste.
            </p>
        </div>
    </div>
</body>

</html>
