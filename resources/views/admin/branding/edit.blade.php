<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marca y Colores') }}
        </h2>
    </x-slot>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    @endpush

    <div class="container py-4" style="max-width: 800px;">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Logo --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Logo</h5>
                </div>
                <div class="card-body">
                    @if($settings->logo_path)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $settings->logo_path) }}"
                                alt="Logo actual"
                                style="max-width: 250px; max-height: 80px; object-fit: contain;"
                                class="border rounded p-2">
                        </div>
                    @endif
                    <div class="mb-0">
                        <label for="logo" class="form-label">Subir nuevo logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                            id="logo" name="logo" accept="image/*">
                        <small class="text-muted">PNG, JPG, SVG, WEBP. Máximo 2MB.</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Colores del Admin --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Colores del Panel Administrativo</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6" x-data="{ color: '{{ old('admin_primary_color', $settings->admin_primary_color ?? '#0d6efd') }}' }">
                            <label class="form-label">Color Primario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" x-model="color">
                                <input type="text" class="form-control @error('admin_primary_color') is-invalid @enderror"
                                    name="admin_primary_color" x-model="color" maxlength="7">
                            </div>
                            <small class="text-muted">Botones, enlaces, badges</small>
                            @error('admin_primary_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" x-data="{ color: '{{ old('admin_secondary_color', $settings->admin_secondary_color ?? '#6c757d') }}' }">
                            <label class="form-label">Color Secundario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" x-model="color">
                                <input type="text" class="form-control @error('admin_secondary_color') is-invalid @enderror"
                                    name="admin_secondary_color" x-model="color" maxlength="7">
                            </div>
                            <small class="text-muted">Elementos secundarios</small>
                            @error('admin_secondary_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Preview Admin --}}
                    <div class="mt-4 p-3 border rounded" x-data="{
                        primary: '{{ old('admin_primary_color', $settings->admin_primary_color ?? '#0d6efd') }}',
                        secondary: '{{ old('admin_secondary_color', $settings->admin_secondary_color ?? '#6c757d') }}'
                    }"
                    x-init="
                        $watch('primary', v => { $refs.btnPrimary.style.backgroundColor = v; $refs.btnPrimary.style.borderColor = v; $refs.badgePrimary.style.backgroundColor = v; });
                        $watch('secondary', v => { $refs.btnSecondary.style.backgroundColor = v; $refs.btnSecondary.style.borderColor = v; })
                    "
                    x-effect="
                        document.querySelector('[name=admin_primary_color]') && (primary = document.querySelector('[name=admin_primary_color]').value);
                        document.querySelector('[name=admin_secondary_color]') && (secondary = document.querySelector('[name=admin_secondary_color]').value)
                    ">
                        <small class="text-muted d-block mb-2">Vista previa:</small>
                        <button type="button" class="btn btn-sm text-white me-2" x-ref="btnPrimary"
                            :style="'background-color:' + primary + ';border-color:' + primary">Primario</button>
                        <button type="button" class="btn btn-sm text-white me-2" x-ref="btnSecondary"
                            :style="'background-color:' + secondary + ';border-color:' + secondary">Secundario</button>
                        <span class="badge text-white" x-ref="badgePrimary"
                            :style="'background-color:' + primary">Admin</span>
                    </div>
                </div>
            </div>

            {{-- Colores del Formulario Público --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Colores del Formulario de Captura</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4" x-data="{ color: '{{ old('form_primary_color', $settings->form_primary_color ?? '#008037') }}' }">
                            <label class="form-label">Color Primario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" x-model="color">
                                <input type="text" class="form-control @error('form_primary_color') is-invalid @enderror"
                                    name="form_primary_color" x-model="color" maxlength="7">
                            </div>
                            <small class="text-muted">Botón cámara, títulos</small>
                            @error('form_primary_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" x-data="{ color: '{{ old('form_secondary_color', $settings->form_secondary_color ?? '#0065B3') }}' }">
                            <label class="form-label">Color Secundario</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" x-model="color">
                                <input type="text" class="form-control @error('form_secondary_color') is-invalid @enderror"
                                    name="form_secondary_color" x-model="color" maxlength="7">
                            </div>
                            <small class="text-muted">Textos destacados, spinner</small>
                            @error('form_secondary_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" x-data="{ color: '{{ old('form_background_color', $settings->form_background_color ?? '#f7f9fa') }}' }">
                            <label class="form-label">Color de Fondo</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" x-model="color">
                                <input type="text" class="form-control @error('form_background_color') is-invalid @enderror"
                                    name="form_background_color" x-model="color" maxlength="7">
                            </div>
                            <small class="text-muted">Fondo de la página</small>
                            @error('form_background_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Preview Formulario --}}
                    <div class="mt-4 border rounded overflow-hidden" x-data="{
                        primary: '{{ old('form_primary_color', $settings->form_primary_color ?? '#008037') }}',
                        secondary: '{{ old('form_secondary_color', $settings->form_secondary_color ?? '#0065B3') }}',
                        bg: '{{ old('form_background_color', $settings->form_background_color ?? '#f7f9fa') }}'
                    }"
                    x-effect="
                        if(document.querySelector('[name=form_primary_color]')) primary = document.querySelector('[name=form_primary_color]').value;
                        if(document.querySelector('[name=form_secondary_color]')) secondary = document.querySelector('[name=form_secondary_color]').value;
                        if(document.querySelector('[name=form_background_color]')) bg = document.querySelector('[name=form_background_color]').value;
                    ">
                        <div class="p-3 text-center" :style="'background-color:' + bg">
                            <small class="text-muted d-block mb-2">Vista previa del formulario:</small>
                            <p class="fw-bold mb-2" :style="'color:' + secondary">Hola, Participante</p>
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 text-white mb-2"
                                :style="'background-color:' + primary + ';width:60px;height:60px;font-size:1.5rem;'">
                                <i class="fas fa-camera"></i>
                            </div>
                            <p class="small mb-0" :style="'color:' + secondary">Subir factura</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contenido del Formulario de Captura --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Contenido del Formulario de Captura</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="form_instructions" class="form-label">Texto Instructivo</label>
                        <textarea class="form-control @error('form_instructions') is-invalid @enderror"
                            id="form_instructions" name="form_instructions">{{ old('form_instructions', $settings->form_instructions) }}</textarea>
                        @error('form_instructions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="form_example_image" class="form-label">Imagen de Ejemplo</label>
                        @if($settings->form_example_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $settings->form_example_image) }}"
                                    alt="Imagen ejemplo actual"
                                    style="max-width: 100%; max-height: 200px; object-fit: contain;"
                                    class="border rounded p-1">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('form_example_image') is-invalid @enderror"
                            id="form_example_image" name="form_example_image" accept="image/*">
                        <small class="text-muted">Imagen guía que muestra cómo tomar la foto. PNG, JPG, WEBP. Máximo 3MB.</small>
                        @error('form_example_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-es-ES.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form_instructions').summernote({
                lang: 'es-ES',
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['font', ['strikethrough']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ],
                placeholder: 'Escribe las instrucciones que verá el participante...',
            });
        });
    </script>
</x-app-layout>
