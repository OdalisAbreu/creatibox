<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro – Maeno&Co</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --maeno-blue: #003d74;
            --maeno-blue-dark: #002d57;
            --maeno-blue-light: rgba(0, 61, 116, 0.08);
            --maeno-charcoal: #2d2d2d;
            --maeno-gray: #6b6b6b;
            --maeno-border: #e0e6eb;
            --maeno-bg: #f0f4f8;
        }
        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            font-size: 1rem;
            color: var(--maeno-charcoal);
            background: var(--maeno-bg);
            padding: 1.5rem 0 3rem;
            min-height: 100vh;
        }
        .maeno-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem 1rem;
            background: var(--maeno-blue);
            border-bottom: 3px solid var(--maeno-blue-dark);
        }
        .maeno-header .logo {
            max-width: 180px;
            height: auto;
            display: inline-block;
        }
        @media (min-width: 576px) {
            .maeno-header .logo { max-width: 220px; }
        }
        .form-card {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-card .page-title {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--maeno-blue);
            margin-bottom: 0.35rem;
        }
        .form-card .page-subtitle {
            font-size: 0.875rem;
            color: var(--maeno-gray);
        }
        .form-section {
            background: #fff;
            border: 1px solid var(--maeno-border);
            border-left: 4px solid var(--maeno-blue);
            border-radius: 8px;
            padding: 1.25rem 1.25rem;
            margin-bottom: 1.25rem;
        }
        .form-section h6 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--maeno-blue);
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }
        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--maeno-charcoal);
        }
        .form-control, .form-select {
            border-radius: 6px;
            border-color: var(--maeno-border);
            font-size: 0.9375rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--maeno-blue);
            box-shadow: 0 0 0 1px var(--maeno-blue);
        }
        .form-check-input:checked {
            background-color: var(--maeno-blue);
            border-color: var(--maeno-blue);
        }
        .btn-maeno {
            background: var(--maeno-blue);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.03em;
        }
        .btn-maeno:hover {
            background: var(--maeno-blue-dark);
            color: #fff;
        }
        .btn-maeno-outline {
            background: transparent;
            color: var(--maeno-blue);
            border: 1px solid var(--maeno-blue);
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-size: 0.875rem;
        }
        .btn-maeno-outline:hover {
            background: var(--maeno-blue-light);
            border-color: var(--maeno-blue);
            color: var(--maeno-blue);
        }
        .alert {
            border-radius: 0;
            border: 1px solid transparent;
        }
        input[type="tel"] { font-variant-numeric: tabular-nums; }
        @media (min-width: 576px) {
            body { padding: 2rem 0 4rem; }
            .form-section { padding: 1.5rem 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="container form-card">
        <header class="maeno-header">
            <a href="{{ url('/customer/form') }}">
                <img src="{{ asset('images/logo-maeno.png') }}" alt="Maeno&amp;Co" class="logo">
            </a>
        </header>

        <div class="mb-4">
            <h1 class="page-title">Formulario de registro</h1>
            <p class="page-subtitle">Complete los campos. Los marcados con * son obligatorios.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Revise los errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('customers.store') }}" id="customerForm">
            @csrf

            <div class="form-section">
                <h6>Datos personales</h6>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="given-name">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="last_name" class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required maxlength="255" autocomplete="family-name">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" inputmode="email">
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">Teléfono celular <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required maxlength="20" placeholder="Solo números, sin espacios" inputmode="numeric" pattern="[0-9]*">
                        <div class="form-text">Solo números, sin espacios.</div>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Dirección física <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required maxlength="500" autocomplete="street-address">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="date_of_birth" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h6>Redes sociales</h6>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label for="instagram" class="form-label">Usuario de Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}" maxlength="100" placeholder="@usuario">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="tiktok" class="form-label">Usuario de TikTok</label>
                        <input type="text" class="form-control" id="tiktok" name="tiktok" value="{{ old('tiktok') }}" maxlength="100" placeholder="@usuario">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h6>Perfil</h6>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="age_range" class="form-label">Rango de edad</label>
                        <select class="form-select" id="age_range" name="age_range">
                            <option value="">Seleccione...</option>
                            @foreach (\App\Models\Customer::AGE_RANGES as $value => $label)
                                <option value="{{ $value }}" {{ old('age_range') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="gender" class="form-label">Género <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Seleccione...</option>
                            @foreach (\App\Models\Customer::GENDERS as $value => $label)
                                <option value="{{ $value }}" {{ old('gender') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="occupation" class="form-label">Ocupación laboral <span class="text-danger">*</span></label>
                        <select class="form-select" id="occupation" name="occupation" required>
                            <option value="">Seleccione...</option>
                            @foreach (\App\Models\Customer::OCCUPATIONS as $opt)
                                <option value="{{ $opt }}" {{ old('occupation') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12" id="wrap_occupation_other" style="{{ old('occupation') === 'Otro' ? '' : 'display:none;' }}">
                        <label for="occupation_other" class="form-label">Especifique ocupación (Otro)</label>
                        <input type="text" class="form-control" id="occupation_other" name="occupation_other" value="{{ old('occupation_other') }}" maxlength="255" placeholder="Indique su ocupación">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h6>Intereses (puede elegir varios)</h6>
                <p class="small text-muted mb-2">Seleccione todos los que apliquen.</p>
                <div class="row g-2">
                    @foreach ($interests as $interest)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="interests[]" value="{{ $interest->id }}" id="int_{{ $interest->id }}" {{ in_array((string) $interest->id, old('interests', [])) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="int_{{ $interest->id }}">{{ $interest->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mb-4">
                <button type="submit" class="btn btn-maeno">Enviar</button>
                <button type="reset" class="btn btn-maeno-outline">Limpiar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var phone = document.getElementById('phone');
            if (phone) {
                phone.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '');
                });
            }
            var occupation = document.getElementById('occupation');
            var wrapOther = document.getElementById('wrap_occupation_other');
            if (occupation && wrapOther) {
                occupation.addEventListener('change', function () {
                    wrapOther.style.display = this.value === 'Otro' ? 'block' : 'none';
                    document.getElementById('occupation_other').required = this.value === 'Otro';
                });
            }
        })();
    </script>
</body>
</html>
