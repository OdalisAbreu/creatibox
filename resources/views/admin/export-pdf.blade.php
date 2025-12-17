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
            border: 1px solid #425968;
            padding: 6px 8px;
        }

        th {
            background: #C41230;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #F5F5F5;
        }

        tbody tr:nth-child(odd) {
            background: #FFFFFF;
        }

        tbody td {
            color: #333;
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
                <th>No.</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Departamento</th>
                <th>Sucursal</th>
                <th>Colaborador</th>
                <th>Fecha Registro</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($captures as $capture)
            <tr>
                <td>{{ $capture->id }}</td>
                <td>{{ $capture->Code ?? '-' }}</td>
                <td>{{ $capture->Description ?? '-' }}</td>
                <td>{{ $capture->department ?? '-' }}</td>
                <td>{{ $capture->sucursal ?? '-' }}</td>
                <td>{{ $capture->collaborator ?? '-' }}</td>
                <td>{{ $capture->created_at->format('d/m/Y') }}</td>
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