@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Crear Cita</h1>

            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="doctor_id" class="form-label">Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                        <option value="">Seleccionar doctor</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->name }} - {{ $doctor->specialty }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="appointment_date" class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" name="appointment_date" id="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" required>
                    @error('appointment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="reason" class="form-label">Razón de la Cita</label>
                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" required></textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Notas Adicionales</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"></textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Crear Cita</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection

