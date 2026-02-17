@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Doctores</h1>

            @if($doctors->count())
                <div class="row">
                    @foreach($doctors as $doctor)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $doctor->user->name }}</h5>
                                <p class="card-text"><strong>Especialidad:</strong> {{ $doctor->specialty ?? 'N/A' }}</p>
                                <p class="card-text"><strong>Licencia:</strong> {{ $doctor->license_number }}</p>
                                <p class="card-text"><strong>Teléfono:</strong> {{ $doctor->phone ?? 'N/A' }}</p>
                                <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary">Ver Perfil</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{ $doctors->links() }}
            @else
                <p>No hay doctores registrados.</p>
            @endif
        </div>
    </div>
</div>
@endsection

