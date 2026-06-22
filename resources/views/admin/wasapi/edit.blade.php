<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración de Wasapi') }}
        </h2>
    </x-slot>

    <div class="container py-4" style="max-width: 700px;">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fab fa-whatsapp me-2 text-success"></i>Cuenta Wasapi</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.wasapi.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            id="phone" name="phone"
                            value="{{ old('phone', $account->phone) }}"
                            placeholder="Ej: 18091234567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" x-data="{ show: false }">
                        <label for="token" class="form-label">Token</label>
                        <div class="input-group">
                            <input :type="show ? 'text' : 'password'"
                                class="form-control @error('token') is-invalid @enderror"
                                id="token" name="token"
                                value="{{ old('token', $account->token) }}">
                            <button type="button" class="btn btn-outline-secondary" @click="show = !show">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('token')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="wasapi_id" class="form-label">Wasapi ID</label>
                        <input type="text" class="form-control @error('wasapi_id') is-invalid @enderror"
                            id="wasapi_id" name="wasapi_id"
                            value="{{ old('wasapi_id', $account->wasapi_id) }}">
                        @error('wasapi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="final_message" class="form-label">Mensaje Final</label>
                        <textarea class="form-control @error('final_message') is-invalid @enderror"
                            id="final_message" name="final_message"
                            rows="4"
                            placeholder="Mensaje que se envía al participante después de subir la factura">{{ old('final_message', $account->final_message) }}</textarea>
                        @error('final_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
