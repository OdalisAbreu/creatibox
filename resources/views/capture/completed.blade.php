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
            --christmas-red: #dc3545;
            --christmas-green: #28a745;
        }

        body {
            font-family: "Poppins", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f7f9fa 0%, #fff3e0 50%, #fce4ec 100%);
            padding: 1rem 0;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Efecto de nieve sutil */
        .snowflake {
            position: fixed;
            top: -10px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8em;
            font-family: Arial;
            animation: fall linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }

        /* Decoraciones navideñas sutiles */
        .christmas-decoration {
            position: fixed;
            font-size: 1.2rem;
            opacity: 0.3;
            animation: float 4s ease-in-out infinite;
            z-index: 1;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        .decoration-top-left {
            left: 20px;
            top: 10%;
        }

        .decoration-top-right {
            right: 20px;
            top: 15%;
        }

        .decoration-bottom-left {
            left: 15px;
            bottom: 20%;
        }

        .decoration-bottom-right {
            right: 15px;
            bottom: 15%;
        }

        .confirm-card {
            width: 90vw;
            max-width: 420px;
            margin: 2rem auto;
            padding: 2rem 1.5rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1), 0 0 30px rgba(220, 53, 69, 0.1);
            text-align: center;
            position: relative;
            z-index: 10;
            border: 2px solid transparent;
            background-image: linear-gradient(white, white), 
                              linear-gradient(135deg, var(--christmas-red) 0%, var(--christmas-green) 100%);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .confirm-card h1 {
            color: var(--christmas-green);
            font-size: 6vw;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .confirm-card p {
            color: #333;
            font-size: 4.5vw;
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
            color: #fff;
            background: var(--whatsapp-green);
            border: none;
            border-radius: .5rem;
            text-decoration: none;
            box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .1), 0 0 15px rgba(37, 211, 102, 0.3);
            transition: all .3s ease-in;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .whatsapp-btn::before {
            content: '✨';
            position: absolute;
            left: -20px;
            animation: sparkle 2s ease-in-out infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: translateX(0); }
            50% { opacity: 1; transform: translateX(350px); }
        }

        .whatsapp-btn:hover {
            transform: scale(1.05);
            text-decoration: none;
            color: #fff;
            box-shadow: 0 .35rem .7rem rgba(0, 0, 0, .15), 0 0 25px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-btn .bi-whatsapp {
            font-size: 1.5em;
            margin-right: .5rem;
        }

        .redirect-note {
            font-size: 3.5vw;
            color: #666;
        }

        /* Tablet and desktop adjustments */
        @media (min-width: 768px) {
            .confirm-card {
                width: 80vw;
                max-width: 500px;
                padding: 3rem 2rem;
            }

            .confirm-card h1 {
                font-size: 2.5rem;
            }

            .confirm-card p {
                font-size: 1.125rem;
            }

            .whatsapp-btn {
                width: 250px;
                font-size: 1rem;
            }

            .redirect-note {
                font-size: .875rem;
            }
        }

        @media (min-width: 1200px) {
            .confirm-card {
                width: 60vw;
                max-width: 600px;
                padding: 4rem 3rem;
            }
        }
    </style>
</head>

<body>
    <!-- Efecto de nieve sutil -->
    <div id="snowflakes"></div>

    <!-- Decoraciones navideñas sutiles -->
    <div class="christmas-decoration decoration-top-left">🎄</div>
    <div class="christmas-decoration decoration-top-right">🎁</div>
    <div class="christmas-decoration decoration-bottom-left">⭐</div>
    <div class="christmas-decoration decoration-bottom-right">🎄</div>

    <div class="container-fluid px-3">
        <div class="confirm-card">
            <h1>🎄 ¡Factura enviada con éxito! 🎅</h1>
            <p>Ya hemos recibido tu factura. ¡Muchas gracias y felices fiestas! ✨</p>

            {{-- Botón volver a WhatsApp --}}
            <a href="https://wa.me/{{ $wasapiAccount->phone }}"  class="whatsapp-btn" target="_blank" rel="noopener">
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
        // Efecto de nieve sutil
        function createSnowflake() {
            const snowflake = document.createElement('div');
            snowflake.className = 'snowflake';
            snowflake.innerHTML = '❄';
            snowflake.style.left = Math.random() * 100 + '%';
            snowflake.style.animationDuration = (Math.random() * 4 + 3) + 's';
            snowflake.style.opacity = Math.random() * 0.5 + 0.3;
            snowflake.style.fontSize = (Math.random() * 8 + 8) + 'px';
            document.getElementById('snowflakes').appendChild(snowflake);

            setTimeout(() => {
                snowflake.remove();
            }, 7000);
        }

        // Crear nieve continuamente (menos frecuente para ser más sutil)
        setInterval(createSnowflake, 500);

        // Countdown visual
        let seconds = 5;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) clearInterval(interval);
        }, 1000);

       //  Redirección automática tras 5 s
        setTimeout(() => {
            window.location.href = 'https://wa.me/{{ $wasapiAccount->phone }}';
        }, 5000);
    </script>
</body>

</html>