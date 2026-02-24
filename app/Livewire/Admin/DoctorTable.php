<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
    }

    public function builder(): Builder
    {
        return Doctor::query()
            ->with(['user', 'speciality']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),

            Column::make("Nombre", "user.name")
                ->sortable()
                ->searchable(),

            Column::make("Especialidad", "speciality.name")
                ->sortable(),

            Column::make("Cédula", "medical_license_number")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?: 'N/A'),

            Column::make("Teléfono", "user.phone")
                ->sortable()
                ->searchable(),

            Column::make("Fecha Registro", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('d/m/Y')),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.doctors.actions', ['doctor' => $row])),
        ];
    }
}
