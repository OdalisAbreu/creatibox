{{-- resources/views/capture/form.blade.php – Promo "Arma tu combo y gana" --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Subir Factura – Promo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --brand-red: #c41e3a;
            --brand-red-dark: #a01830;
            --brand-white: #ffffff;
            --accent-orange: #f59e0b;
            --shadow-gray: rgba(0, 0, 0, 0.08);
            --text-muted: #666666;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: linear-gradient(180deg, #fef2f2 0%, #fff 50%);
            padding: 1rem 0;
            min-height: 100vh;
            position: relative;
        }

        .header-section {
            background: var(--brand-red);
            color: var(--brand-white);
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(196, 30, 58, 0.25);
            text-align: center;
        }

        .header-section .promo-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            opacity: 0.95;
            margin-bottom: 0.5rem;
        }

        .header-section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-section .subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .instructions-card,
        .example-section {
            background: var(--brand-white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 12px var(--shadow-gray);
            border: 1px solid rgba(196, 30, 58, 0.1);
        }

        .instructions-card h3,
        .example-section h4 {
            color: var(--brand-red);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .example-section h4 {
            justify-content: center;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .requirements-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: #495057;
            font-size: 0.9rem;
        }

        .requirements-list li:last-child {
            border-bottom: none;
        }

        .requirements-list li .icon {
            color: var(--brand-red);
            font-size: 1.2rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .example-image-container {
            position: relative;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 2px 12px var(--shadow-gray);
            border: 2px dashed var(--brand-red);
        }

        .example-image-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .example-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--brand-red);
            color: var(--brand-white);
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .capture-card {
            width: 90vw;
            max-width: 420px;
            margin: 0 auto;
            border-radius: 1rem;
            border: 2px solid var(--brand-red);
            background: var(--brand-white);
            box-shadow: 0 4px 20px var(--shadow-gray);
        }

        .camera-label {
            width: 50vw;
            height: 50vw;
            max-width: 160px;
            max-height: 150px;
            border-radius: 35%;
            background: var(--brand-red);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-white);
            font-size: clamp(3rem, 15vw, 8rem);
            box-shadow: 0 4px 16px rgba(196, 30, 58, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .camera-label i {
            position: relative;
            z-index: 1;
        }

        .camera-label:hover,
        .camera-label:focus {
            transform: scale(1.05);
            cursor: pointer;
            background: var(--brand-red-dark);
            box-shadow: 0 6px 20px rgba(196, 30, 58, 0.45);
        }

        #invoice_image {
            position: absolute;
            left: -9999px;
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1055;
            font-size: 1.25rem;
            color: var(--brand-red);
        }

        .loading-overlay.show {
            display: flex;
        }

        .info-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-align: center;
            margin-top: 1rem;
            padding: 0 1rem;
        }

        @media (min-width: 768px) {
            .camera-label {
                width: 200px;
                height: 200px;
                font-size: 5rem;
            }
            .header-section {
                padding: 2.5rem 2rem;
            }
            .header-section h2 {
                font-size: 1.75rem;
            }
            .instructions-card,
            .example-section {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3" style="max-width: 500px; margin: 0 auto;">
        <div class="header-section">
            <div class="promo-badge">ARMA TU COMBO Y GANA</div>
            <h2>¡Hola, {{ $capture->name }}!</h2>
            <p class="subtitle mb-0">Por favor, envíanos una foto clara de tu factura de compra.</p>
        </div>

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        @endif

        <form id="captureForm"
            method="POST"
            action="{{ route('capture.submitImage', ['cell_phone' => $capture->cell_phone]) }}"
            enctype="multipart/form-data"
            class="card p-4 capture-card mb-3">

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
                <i class="bi bi-file-earmark-image"></i>
                Formatos admitidos: JPG, PNG, HEIC (máx. 3 MB)
            </p>
            <p class="text-center small text-muted mt-2 mb-0">
                <i class="bi bi-camera"></i>
                Haz clic en el botón de arriba para tomar o seleccionar la foto
            </p>
        </form>

        <div class="instructions-card">
            <h3>
                <i class="bi bi-info-circle-fill"></i>
                Requisitos importantes
            </h3>
            <p class="text-muted small mb-3">Asegúrate de que en la foto se vea claramente:</p>
            <ul class="requirements-list">
                <li>
                    <i class="bi bi-check-circle-fill icon"></i>
                    <span><strong>Nombre del establecimiento</strong> donde realizaste la compra</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill icon"></i>
                    <span><strong>Fecha de la compra</strong> visible y legible</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill icon"></i>
                    <span><strong>Número de factura o NCF</strong> completo</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill icon"></i>
                    <span><strong>Productos participantes:</strong> Chivas, Jameson, Absolut, Beefeater, The Glenlivet</span>
                </li>
            </ul>
        </div>

        <div class="example-section">
            <h4>
                <i class="bi bi-image"></i>
                Ejemplo de factura
            </h4>
            <div class="example-image-container">
                <span class="example-badge">EJEMPLO</span>
                <img src="{{ asset('images/example.png') }}" alt="Ejemplo de factura">
            </div>
            <p class="info-text">
                <i class="bi bi-lightbulb"></i>
                La foto debe ser clara, con buena iluminación y todos los datos legibles
            </p>
        </div>
    </div>

    <div id="loading" class="loading-overlay">
        <div class="spinner-border me-2" role="status" aria-hidden="true"></div>
        Procesando tu imagen…
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
