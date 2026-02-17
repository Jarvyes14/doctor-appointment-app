@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Detalles de la Cita</h1>

            <div class="card">
                <div class="card-body">
                    <p><strong>Doctor:</strong> {{ $appointment->doctor->user->name }}</p>
                    <p><strong>Fecha:</strong> {{ $appointment->appointment_date->format('d/m/Y H:i') }}</p>
                    <p><strong>Razón:</strong> {{ $appointment->reason }}</p>
                    <p><strong>Notas:</strong> {{ $appointment->notes ?? 'Sin notas' }}</p>
                    <p><strong>Estado:</strong> {{ $appointment->status }}</p>
                </div>
            </div>

            <a href="{{ route('appointments.index') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection

