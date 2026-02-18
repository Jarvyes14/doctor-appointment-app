@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>{{ $doctor->user->name }}</h1>

            <div class="card">
                <div class="card-body">
                    <p><strong>Especialidad:</strong> {{ $doctor->specialty ?? 'N/A' }}</p>
                    <p><strong>Licencia:</strong> {{ $doctor->license_number }}</p>
                    <p><strong>Teléfono:</strong> {{ $doctor->phone ?? 'N/A' }}</p>
                    <p><strong>Dirección:</strong> {{ $doctor->address ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->hasRole('Doctor') && auth()->user()->id === $doctor->user_id)
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-warning mt-3">Editar Perfil</a>
            @endif

            <a href="{{ route('doctors.index') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection

