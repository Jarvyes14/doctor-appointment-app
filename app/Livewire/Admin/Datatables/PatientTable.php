<?php

namespace App\Livewire\Admin\DataTables;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PatientTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // Opcional: Establecer un ordenamiento por defecto
        $this->setDefaultSort('created_at', 'desc');
    }

    public function builder(): Builder
    {
        // Iniciamos la consulta sobre el modelo Patient
        return Patient::query()
            ->with(['user', 'bloodType']); // Cargamos las relaciones necesarias
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),

            Column::make("Nombre", "user.name")
                ->sortable()
                ->searchable(),

            Column::make("Email", "user.email")
                ->sortable()
                ->searchable(),

            // Columna específica para pacientes
            Column::make("Tipo Sangre", "bloodType.name")
                ->label(fn($row) => $row->bloodType?->name ?? 'N/A')
                ->sortable(),

            Column::make("Teléfono", "user.phone")
                ->sortable()
                ->searchable(),

            Column::make("Fecha Registro", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('d/m/Y')),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.patients.actions', ['patient' => $row])),
        ];
    }
}

