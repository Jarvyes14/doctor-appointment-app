@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Editar Perfil de Doctor</h1>

            <form action="{{ route('doctors.update', $doctor) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group mb-3">
                    <label for="specialty" class="form-label">Especialidad</label>
                    <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty', $doctor->specialty) }}" required>
                    @error('specialty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="license_number" class="form-label">Número de Licencia</label>
                    <input type="text" name="license_number" id="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number', $doctor->license_number) }}" required>
                    @error('license_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $doctor->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="address" class="form-label">Dirección</label>
                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $doctor->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection

