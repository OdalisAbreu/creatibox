{{-- resources/views/capture/completed.blade.php – Promo "Arma tu combo y gana" --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura enviada – Promo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --brand-red: #c41e3a;
            --brand-red-dark: #a01830;
            --brand-white: #ffffff;
            --accent-orange: #f59e0b;
            --accent-orange-light: #fbbf24;
            --shadow-gray: rgba(0, 0, 0, 0.08);
            --text-dark: #333333;
            --text-muted: #666666;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: linear-gradient(180deg, #fef2f2 0%, #fff 50%);
            padding: 1rem 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .confirm-card {
            width: 90vw;
            max-width: 420px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            background: var(--brand-white);
            box-shadow: 0 4px 20px var(--shadow-gray), 0 0 0 3px rgba(196, 30, 58, 0.1);
            text-align: center;
            border: 2px solid var(--brand-red);
        }

        .confirm-card h1 {
            color: var(--brand-red);
            font-size: clamp(1.35rem, 5vw, 1.75rem);
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 1px 2px var(--shadow-gray);
            line-height: 1.3;
        }

        .confirm-card p {
            color: var(--text-dark);
            font-size: clamp(0.95rem, 3.5vw, 1.05rem);
            margin-bottom: 1.5rem;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 280px;
            padding: 0.85rem 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--brand-white);
            background: #25D366;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .whatsapp-btn:hover {
            transform: scale(1.02);
            color: var(--brand-white);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.45);
        }

        .whatsapp-btn .bi-whatsapp {
            font-size: 1.4em;
            margin-right: 0.5rem;
        }

        .redirect-note {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 1rem;
        }

        .promo-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--brand-white);
            background: var(--brand-red);
            border-radius: 2rem;
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }

        @media (min-width: 768px) {
            .confirm-card {
                padding: 2.5rem 2rem;
            }

            .confirm-card h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3">
        <div class="confirm-card">
            <span class="promo-badge">ARMA TU COMBO Y GANA</span>
            <h1>¡Factura enviada con éxito!</h1>
            <p>Ya hemos recibido tu factura. Gracias por participar.</p>

            <a href="https://wa.me/{{ $wasapiAccount->phone }}" class="whatsapp-btn" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp"></i>
                Volver a WhatsApp
            </a>

            <div class="redirect-note">
                Serás redirigido a WhatsApp en <span id="countdown">5</span> segundos…
            </div>
        </div>
    </div>

    <script>
        let seconds = 5;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) clearInterval(interval);
        }, 1000);

        setTimeout(() => {
            window.location.href = 'https://wa.me/{{ $wasapiAccount->phone }}';
        }, 5000);
    </script>
</body>

</html>
