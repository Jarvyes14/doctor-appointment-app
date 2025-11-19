<?php

namespace App\Livewire\Admin\DataTables;

use App\Models\User; // Importamos el modelo de usuario
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\View\View;

class UserTable extends DataTableComponent
{
    //protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     * Define la consulta base (Builder) para el DataTable.
     * Filtra los usuarios para incluir solo aquellos con el rol 'user'.
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return User::query()->with('roles');
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
            Column::make("Número Id", "id_number")
                ->sortable()
                ->searchable(),
            Column::make("Telefono", "phone")
                ->sortable()
                ->searchable(),
            Column::make("Rol", "roles")
                ->label(function ($row){
                    return $row->roles->first()?->name ?? 'Sin Rol';
                })
                ->sortable()
                ->searchable(),
            Column::make("Acciones")
                ->label(function ($row){
                    return view('admin.users.actions',
                    ['user' => $row]);
                }),
        ];
    }
}
