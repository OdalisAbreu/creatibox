{{-- resources/views/capture/confirmation.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura Enviada – FacturaCapture</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --fc-green: #008037;
            --fc-blue: #0065B3;
            --bs-primary: var(--fc-green);
            --whatsapp-green: #25D366;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: #f7f9fa;
            padding: 1rem 0;
        }

        .confirm-card {
            width: 90vw;
            max-width: 420px;
            margin: 2rem auto;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
            text-align: center;
        }

        .confirm-card h1 {
            color: var(--fc-green);
            font-size: 6vw;
            max-font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .confirm-card p {
            color: #333;
            font-size: 4.5vw;
            max-font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70vw;
            max-width: 300px;
            padding: .75rem 1rem;
            font-size: 4.5vw;
            max-font-size: 1rem;
            color: #fff;
            background: var(--whatsapp-green);
            border: none;
            border-radius: .5rem;
            text-decoration: none;
            box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .1);
            transition: transform .15s ease-in;
            margin-bottom: 1rem;
        }

        .whatsapp-btn:hover {
            transform: scale(1.03);
            text-decoration: none;
            color: #fff;
        }

        .whatsapp-btn .bi-whatsapp {
            font-size: 1.5em;
            margin-right: .5rem;
        }

        .redirect-note {
            font-size: 3.5vw;
            max-font-size: .875rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3">
        <div class="confirm-card">
            <h1>¡Factura enviada con éxito! 🎉</h1>
            <p>Ya hemos recibido tu factura. Muchas gracias.</p>

            {{-- Botón volver a WhatsApp --}}
            <a href="https://wa.me/18098510363"
                class="whatsapp-btn"
                target="_blank"
                rel="noopener">
                <i class="bi bi-whatsapp"></i>
                Volver a WhatsApp
            </a>

            {{-- Nota de redirección --}}
            <div class="redirect-note">
                Serás redirigido a WhatsApp en <span id="countdown">5</span> segundos…
            </div>
        </div>
    </div>

    <script>
        // Countdown visual
        let seconds = 5;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) clearInterval(interval);
        }, 1000);

        // Redirección automática tras 5s
        setTimeout(() => {
            window.location.href = 'https://wa.me/18098510363';
        }, 5000);
    </script>
</body>

</html>