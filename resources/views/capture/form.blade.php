{{-- resources/views/capture/upload.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Subir Factura – FacturaCapture</title>

    {{-- 1. Meta viewport para móviles --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --fc-green: #008037;
            --fc-blue: #0065B3;
            --bs-primary: var(--fc-green);
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: #f7f9fa;
            padding: 1rem 0;
        }

        /* 2. Card ocupa casi todo el ancho en móviles */
        .capture-card {
            width: 90vw;
            max-width: 420px;
            margin: 0 auto;
            border-radius: 1rem;
            border: 0;
        }

        /* 3. Botón cámara escala con viewport width */
        .camera-label {
            width: 50vw;
            height: 50vw;
            max-width: 280px;
            max-height: 280px;
            border-radius: 35%;
            background: var(--fc-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15vw;
            /* Aumenta el tamaño del ícono */
            max-font-size: 12rem;
            /* Ajusta el tamaño máximo del ícono */
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            transition: transform .15s ease-in;
        }

        .camera-label:hover,
        .camera-label:focus {
            transform: scale(1.05);
            cursor: pointer;
        }

        /* Ocultamos el input real */
        #invoice_image {
            position: absolute;
            left: -9999px;
        }

        /* Overlay de carga */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, .8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1055;
            font-size: 1.25rem;
            color: var(--fc-blue);
        }

        .loading-overlay.show {
            display: flex;
        }

        @media (min-width: 768px) {
            .camera-label {
                width: 200px;
                /* Tamaño fijo para PC */
                height: 200px;
                /* Tamaño fijo para PC */
                font-size: 5rem;
                /* Tamaño del ícono reducido */
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3">
        <h2 class="fw-bold mb-3 text-center text-uppercase" style="color:var(--fc-blue)">
            Hola, {{ $capture->name }} 👋
        </h2>
        <p class="text-center mb-4">Toma una foto o selecciona la imagen de tu factura</p>

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="captureForm"
            method="POST"
            action="{{ route('capture.submitImage', ['cell_phone' => $capture->cell_phone]) }}"
            enctype="multipart/form-data"
            class="card p-4 shadow-sm capture-card">

            @csrf

            <div class="d-flex justify-content-center mb-3">
                <label for="invoice_image" class="camera-label" title="Tomar foto / elegir imagen">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <input type="file"
                    id="invoice_image"
                    name="invoice_image"
                    accept="image/*"
                    capture="environment"
                    required>
            </div>

            <p class="text-center small text-muted mb-0">
                Formatos admitidos: JPG, PNG, HEIC … (máx. 3 MB)
            </p>
        </form>
    </div>

    <div id="loading" class="loading-overlay">
        <div class="spinner-border me-2" role="status" aria-hidden="true"></div>
        Subiendo tu factura…
    </div>

    <script>
        (function() {
            const fileInput = document.getElementById('invoice_image');
            const form = document.getElementById('captureForm');
            const loading = document.getElementById('loading');

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    loading.classList.add('show');
                    form.submit();
                }
            });

            form.addEventListener('submit', () => {
                fileInput.disabled = true;
            });
        })();
    </script>
</body>

</html>