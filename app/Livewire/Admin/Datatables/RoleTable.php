<?php

namespace App\Livewire\Admin\Datatables;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role; // Ajusta según tu modelo

class RoleTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.datatables.role-table', [
            'roles' => $roles
        ]);
    }
}
