<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Eliminar columnas obsoletas si existen
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'specialty')) $table->dropColumn('specialty');
            if (Schema::hasColumn('doctors', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('doctors', 'address')) $table->dropColumn('address');
        });

        // 2. Renombrar license_number a medical_license_number si existe
        if (Schema::hasColumn('doctors', 'license_number')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->renameColumn('license_number', 'medical_license_number');
            });
        }

        // 3. Agregar nuevas columnas
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'medical_license_number')) {
                $table->string('medical_license_number')->default('N/A');
            } else {
                $table->string('medical_license_number')->default('N/A')->change();
            }

            if (!Schema::hasColumn('doctors', 'speciality_id')) {
                $table->foreignId('speciality_id')->constrained('specialities')->onDelete('cascade');
            }

            if (!Schema::hasColumn('doctors', 'biography')) {
                $table->text('biography')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['speciality_id']);
            $table->dropColumn(['speciality_id', 'biography']);
            $table->renameColumn('medical_license_number', 'license_number');
            $table->string('specialty')->nullable();
        });
    }
};
