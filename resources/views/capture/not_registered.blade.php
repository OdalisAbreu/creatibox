<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Número no registrado – FacturaCapture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --fc-green: #008037;
            --fc-blue: #0065B3;
            --whatsapp-green: #25D366;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: #f7f9fa;
            padding: 1rem 0;
        }

        .notice-card {
            width: 90vw;
            max-width: 420px;
            margin: 2rem auto;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
            text-align: center;
        }

        .notice-card h1 {
            color: var(--fc-blue);
            font-size: 1.35rem;
            margin-bottom: 1rem;
        }

        .notice-card p {
            color: #444;
            font-size: 0.95rem;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }

        .notice-card .phone-tried {
            font-weight: 600;
            color: #333;
            word-break: break-all;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 300px;
            padding: .85rem 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            color: #fff !important;
            background: var(--whatsapp-green);
            border: none;
            border-radius: .5rem;
            text-decoration: none;
            box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .12);
            transition: transform .15s ease-in, filter .15s ease;
        }

        .whatsapp-btn:hover {
            transform: scale(1.02);
            filter: brightness(1.05);
            color: #fff !important;
        }

        .whatsapp-btn .bi-whatsapp {
            font-size: 1.35rem;
            margin-right: .5rem;
        }

        @media (min-width: 768px) {
            .notice-card {
                max-width: 480px;
                padding: 2.5rem 2rem;
            }

            .notice-card h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3">
        <div class="notice-card">
            <h1>Número no registrado</h1>
            <p>
                El número <span class="phone-tried">{{ $cell_phone }}</span> no está registrado en la promoción.
                Si crees que es un error, escríbenos por WhatsApp y te ayudamos.
            </p>

            @if (!empty($whatsappDigits))
                <a href="https://wa.me/{{ $whatsappDigits }}" class="whatsapp-btn" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp"></i>
                    Contactar por WhatsApp
                </a>
            @else
                <p class="small text-muted mb-0">No hay un número de contacto configurado. Consulta con el organizador.</p>
            @endif
        </div>
    </div>
</body>

</html>
