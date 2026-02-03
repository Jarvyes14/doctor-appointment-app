<?php

namespace App\Livewire\Admin\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTable extends DataTableComponent
{
    public ?string $roleFilter = null;
    // Por defecto está en true para que se vea en las demás vistas
    public bool $showBloodType = true;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        // Solo cargamos la relación si la vamos a mostrar
        $relations = ['roles'];
        if ($this->showBloodType) {
            $relations[] = 'bloodType';
        }

        $query = User::query()->with($relations);

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        return $query;
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Id", "id")->sortable(),
            Column::make("Nombre", "name")->sortable()->searchable(),
        ];

        // Solo añadimos la columna si la propiedad es verdadera
        if ($this->showBloodType) {
            $columns[] = Column::make("Tipo Sangre", "bloodType.name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    // $value será el nombre del tipo de sangre si existe
                    // Si no existe, forzamos a que muestre 'null' o el texto que prefieras
                    return $value ?: 'N/A';
                });
        }

        // Combinamos con el resto de las columnas
        return array_merge($columns, [
            Column::make("Email", "email")->sortable()->searchable(),
            Column::make("Número Id", "id_number")->sortable()->searchable(),
            Column::make("Telefono", "phone")->sortable()->searchable(),
            Column::make("Rol", "roles")
                ->label(fn($row) => $row->roles->first()?->name ?? 'Sin Rol'),
            Column::make("Acciones")
                ->label(fn($row) => view('admin.users.actions', ['user' => $row])),
        ]);
    }
}
