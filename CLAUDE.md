# CreatiBox - Sistema de Captura de Facturas

## Descripcion
Sistema Laravel para capturar datos de participantes y fotos de facturas/recibos. Los usuarios acceden via un enlace con su telefono, suben la imagen de su factura, y reciben confirmacion por WhatsApp. Los administradores gestionan todo desde un dashboard.

## Stack
- **Backend:** Laravel 10.10, PHP 8.1+, MySQL
- **Frontend:** Bootstrap 5.3, Alpine.js 3.4, Vite 5, Font Awesome
- **Integraciones:** WASAPI (WhatsApp API), Maatwebsite Excel, mPDF

## Estructura Principal

### Modelos
- `Capture` — Registro principal (cell_phone unique, name, card_id, contact_number, storage, completed, number_send_message)
- `CaptureImage` — Imagenes de facturas (capture_id FK, image_path). Relacion many-to-one con Capture
- `User` — Usuarios admin (is_admin boolean)
- `WasapiAccount` — Credenciales WhatsApp (phone, token, wasapi_id, final_message)

### Controladores
- `AdminController` — Dashboard: CRUD de capturas, filtros, exportacion Excel/PDF, subida de imagenes
- `CaptureController` — Formulario publico de captura, subida de imagen, API endpoints
- `ProfileController` — Gestion de perfil de usuario

### Servicios
- `WasapiService` — Envia mensajes WhatsApp via WASAPI API

### Exports
- `CapturesExport` — Export Excel con filtros (Maatwebsite)

## Rutas Clave

### Publicas
- `GET /capture/{cell_phone}` — Formulario de captura
- `POST /capture/image/{cell_phone}` — Subir imagen de factura

### API
- `POST /api/capture/{cell_phone}` — Crear captura
- `GET /api/capture/{cell_phone}` — Obtener datos de captura

### Admin (auth)
- `GET /admin` — Dashboard con filtros y paginacion
- `POST /admin/store-capture` — Crear captura manual
- `PUT /admin/update/{id}` — Actualizar captura
- `DELETE /admin/delete/{id}` — Eliminar imagen
- `GET /admin/export/excel` — Exportar Excel
- `GET /admin/export/pdf` — Exportar PDF

## Flujo Principal
1. Se crea captura via API (`completed=false`)
2. Usuario accede a `/capture/{phone}` y sube foto de factura
3. Se guarda imagen en `storage/app/public/invoices/`, se crea `CaptureImage`, `completed=true`
4. Se envia mensaje WhatsApp de confirmacion via WasapiService
5. Admin gestiona desde dashboard con filtros por nombre, telefono, fechas, estado

## Base de Datos
- Driver: MySQL (127.0.0.1:3306, db: creatibox)
- Charset: utf8mb4

## Comandos
```bash
php artisan serve          # Iniciar servidor
php artisan count:records  # Contar registros en BD
```

## Notas
- Imagenes se guardan en disco `public` bajo `invoices/`
- El middleware `IsAdmin` existe pero no esta aplicado a las rutas admin
- Las rutas API no tienen autenticacion Sanctum activa
- card_id se sanitiza removiendo espacios y no-digitos
- Dashboard muestra por defecto capturas de los ultimos 3 dias
