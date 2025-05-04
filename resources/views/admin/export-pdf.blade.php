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
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Género</th>
                <th>Edad</th>
                <th>Cédula</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
                <th>Fecha Factura</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($captures as $capture)
            <tr>
                <td>{{ $capture->id }}</td>
                <td>{{ $capture->name }}</td>
                <td>{{ $capture->cell_phone }}</td>
                <td>{{ $capture->email }}</td>
                <td>{{ ucfirst($capture->gender) }}</td>
                <td>{{ $capture->age }}</td>
                <td>{{ $capture->card_id }}</td>
                <td>
                    @if(Storage::disk('public')->exists($capture->image_path))
                    <img src="{{ 'file://'.public_path('storage/'.$capture->image_path) }}" width="120">
                    @else
                    <span>Imagen no disponible</span>
                    @endif
                </td>
                <td>{{ $capture->estado }}</td>
                <td>{{ \Carbon\Carbon::parse($capture->created_at)->format('d/m/Y H:i') }}</td>
                <td>
                    @if($capture->invoice_created_at)
                    {{ \Carbon\Carbon::parse($capture->invoice_created_at)->format('d/m/Y H:i') }}
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