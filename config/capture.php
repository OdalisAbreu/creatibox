<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facturas subidas por el cliente
    |--------------------------------------------------------------------------
    |
    | Tras validar el tamaño máximo del upload, la imagen puede redimensionarse
    | si excede el lado mayor indicado (píxeles) y guardarse como JPEG con la
    | calidad indicada (40–95).
    |
    */
    'invoice_max_dimension' => (int) env('CAPTURE_INVOICE_MAX_DIMENSION', 2560),

    'invoice_jpeg_quality' => (int) env('CAPTURE_INVOICE_JPEG_QUALITY', 82),

];
