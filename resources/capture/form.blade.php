<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Subir Factura - FacturaCapture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">
    <div class="container">
        <h1 class="mb-4">Hola, {{ $capture->name }} 👋</h1>
        <p>Por favor, sube una foto de tu factura:</p>

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ url('/captura/' . $capture->cell_phone) }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
            @csrf
            <div class="mb-3">
                <label for="invoice_image" class="form-label">Selecciona una imagen (máx 3MB):</label>
                <input class="form-control" type="file" id="invoice_image" name="invoice_image" accept="image/*" required>
                @error('invoice_image')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Subir Factura</button>
        </form>
    </div>
</body>

</html>