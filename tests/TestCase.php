<?php

namespace Tests;

use App\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Limpiar la caché de Spatie para evitar conflictos en tests
        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        // 2. Crear el rol que pide tu modelo User.php
        Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Doctor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Paciente', 'guard_name' => 'web']);
    }
}
