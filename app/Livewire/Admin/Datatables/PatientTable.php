<?php

namespace App\Livewire\Admin\DataTables;

use App\Models\User;
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
        // Iniciamos la consulta sobre el modelo User
        return User::query()
            // Filtramos para obtener solo los que tienen rol 'patient'
            // Asegúrate de que el string coincida con el nombre de tu rol en la BD
            ->role('patient') 
            ->with(['bloodType']); // Cargamos la relación de tipo de sangre
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),

            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),

            Column::make("Email", "email")
                ->sortable()
                ->searchable(),

            // Columna específica para pacientes
            Column::make("Tipo Sangre", "bloodType.name")
                ->label(fn($row) => $row->bloodType?->name ?? 'N/A')
                ->sortable(),

            Column::make("Teléfono", "phone")
                ->sortable()
                ->searchable(),

            Column::make("Fecha Registro", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('d/m/Y')),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.users.actions', ['user' => $row])),
        ];
    }
}