@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Mis Citas</h1>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">Nueva Cita</a>

            @if($appointments->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Fecha</th>
                            <th>Razón</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $appointment->reason }}</td>
                            <td>{{ $appointment->status }}</td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $appointments->links() }}
            @else
                <p>No hay citas registradas.</p>
            @endif
        </div>
    </div>
</div>
@endsection

