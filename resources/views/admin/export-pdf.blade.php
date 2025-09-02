{{-- resources/views/admin/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Capturas</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111
        }

        .title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px 6px;
        }

        th {
            background: #f1f1f1;
        }

        img {
            display: block;
            width: 120px;
            height: auto;
            object-fit: cover;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <h2 class="title">Reporte de Capturas {{ now()->format('d/m/Y H:i') }}</h2>

    <table>
        <thead>
            <tr>
                <th>Número de Factura</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Número de Contacto</th>
                <th>Ciudad</th>
                <th>Establecimiento </th>
                <th>Fecha Registro</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($captures as $capture)
            <tr>
                <td>{{ $capture->invoice_number }}</td>
                <td>{{ $capture->name }}</td>
                <td>{{ $capture->last_name }}</td>
                <td>{{ $capture->card_id }}</td>
                <td>{{ $capture->contact_number ?? $capture->cell_phone }}</td>
                <td>{{ $capture->city }}</td>
                <td>{{ $capture->storage }}</td>
                <td> {{ $capture->created_at->format('d/m/Y') }}</td>
                <td>
                    @php
                        $path = $capture->image_path;
                    @endphp

                    @if (!empty($path) && Storage::disk('public')->exists($path))
                        <img src="{{ 'file://' . public_path('storage/' . $path) }}"
                            width="80" height="80" />
                    @else
                        <span>Imagen no disponible</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Página <span class="page-number"></span>
    </footer>

    <script type="text/php">
        // -- numeración de páginas en DomPDF --
    if ( isset($pdf) ) {
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $pdf->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 8, [0,0,0]);
    }
</script>

</body>

</html>