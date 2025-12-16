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
            --christmas-red: #dc3545;
            --christmas-green: #28a745;
            --christmas-gold: #ffc107;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #fff3e0 50%, #fce4ec 100%);
            padding: 1rem 0;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Efecto de nieve */
        .snowflake {
            position: fixed;
            top: -10px;
            color: white;
            font-size: 1em;
            font-family: Arial;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
            animation: fall linear infinite;
            pointer-events: none;
            z-index: 1000;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }

        /* Decoraciones navideñas */
        .christmas-decoration {
            position: absolute;
            font-size: 1.5rem;
            animation: float 3s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .decoration-left {
            left: 10px;
            top: 20%;
        }

        .decoration-right {
            right: 10px;
            top: 30%;
        }

        .header-section {
            background: linear-gradient(135deg, var(--christmas-red) 0%, #c82333 50%, var(--christmas-green) 100%);
            color: white;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '🎄';
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 2rem;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite;
        }

        .header-section::after {
            content: '🎅';
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 2rem;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite 1.5s;
        }

        .header-section h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-section .subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .instructions-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .instructions-card h3 {
            color: var(--christmas-red);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            color: var(--christmas-green);
            font-size: 1.2rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .example-section {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .example-section h4 {
            color: var(--christmas-red);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
        }

        .example-image-container {
            position: relative;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid var(--christmas-green);
            border-style: dashed;
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
            background: linear-gradient(135deg, var(--christmas-red) 0%, var(--christmas-green) 100%);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* 2. Card ocupa casi todo el ancho en móviles */
        .capture-card {
            width: 90vw;
            max-width: 420px;
            margin: 0 auto;
            border-radius: 1rem;
            border: 0;
            background: white;
        }

        /* 3. Botón cámara escala con viewport width - Estilo Navideño */
        .camera-label {
            width: 50vw;
            height: 50vw;
            max-width: 160px;
            max-height: 150px;
            border-radius: 35%;
            background: var(--christmas-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: clamp(3rem, 15vw, 8rem);
            box-shadow: 0 .5rem 1rem rgba(40, 167, 69, 0.4), 0 0 20px rgba(40, 167, 69, 0.3);
            transition: all .3s ease-in;
            position: relative;
            overflow: hidden;
        }

        .camera-label::before {
            content: '📸';
            position: absolute;
            font-size: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .camera-label i {
            position: relative;
            z-index: 1;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .camera-label:hover,
        .camera-label:focus {
            transform: scale(1.05) rotate(5deg);
            cursor: pointer;
            background: #218838;
            box-shadow: 0 .8rem 1.5rem rgba(40, 167, 69, 0.5), 0 0 30px rgba(40, 167, 69, 0.4);
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

        .info-text {
            color: #6c757d;
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

            .instructions-card,
            .example-section {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Efecto de nieve -->
    <div id="snowflakes"></div>

    <!-- Decoraciones navideñas -->
    <div class="christmas-decoration decoration-left">🎄</div>
    <div class="christmas-decoration decoration-right">🎁</div>

    <div class="container-fluid px-3" style="max-width: 500px; margin: 0 auto; position: relative; z-index: 10;">
        <!-- Header Section -->
        <div class="header-section text-center">
            <h2>🎄 ¡Hola! 🎅</h2>
            <p class="subtitle mb-0">✨ Por favor, envíanos una foto clara de tu factura de compra ✨</p>
            @if($capture->Description)
                <p class="subtitle mb-0 mt-2"><strong>Código:</strong> {{ $capture->Code }}</p>
                <p class="subtitle mb-0"><strong>Descripción:</strong> {{ $capture->Description }}</p>
            @endif
        </div>

        <!-- Error Messages -->
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Upload Form -->
        <form id="captureForm"
            method="POST"
            action="{{ route('capture.submitImage', ['code' => $capture->Code]) }}"
            enctype="multipart/form-data"
            class="card p-4 shadow-sm capture-card mb-3">

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

        <!-- Instructions Card -->
        <div class="instructions-card">
            <h3>
                <i class="bi bi-info-circle-fill"></i>
                🎯 Requisitos importantes
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

        <!-- Example Section -->
        <div class="example-section">
            <h4>
                <i class="bi bi-image"></i>
                📷 Ejemplo de factura
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
        // Efecto de nieve
        function createSnowflake() {
            const snowflake = document.createElement('div');
            snowflake.className = 'snowflake';
            snowflake.innerHTML = '❄';
            snowflake.style.left = Math.random() * 100 + '%';
            snowflake.style.animationDuration = (Math.random() * 3 + 2) + 's';
            snowflake.style.opacity = Math.random();
            snowflake.style.fontSize = (Math.random() * 10 + 10) + 'px';
            document.getElementById('snowflakes').appendChild(snowflake);

            setTimeout(() => {
                snowflake.remove();
            }, 5000);
        }

        // Crear nieve continuamente
        setInterval(createSnowflake, 300);

        // Funcionalidad del formulario
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