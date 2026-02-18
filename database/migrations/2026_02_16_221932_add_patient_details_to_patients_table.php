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
        Schema::table('patients', function (Blueprint $table) {
            // Solo agregar columnas que no existan
            if (!Schema::hasColumn('patients', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('patients', 'gender')) {
                $table->enum('gender', ['Masculino', 'Femenino', 'Otro'])->nullable();
            }
            if (!Schema::hasColumn('patients', 'occupation')) {
                $table->string('occupation')->nullable();
            }
            if (!Schema::hasColumn('patients', 'family_medical_history')) {
                $table->text('family_medical_history')->nullable();
            }
            if (!Schema::hasColumn('patients', 'chronic_diseases')) {
                $table->text('chronic_diseases')->nullable();
            }
            if (!Schema::hasColumn('patients', 'medications')) {
                $table->text('medications')->nullable();
            }
            if (!Schema::hasColumn('patients', 'general_health_notes')) {
                $table->text('general_health_notes')->nullable();
            }
            if (!Schema::hasColumn('patients', 'smoker')) {
                $table->boolean('smoker')->default(false);
            }
            if (!Schema::hasColumn('patients', 'drinker')) {
                $table->boolean('drinker')->default(false);
            }
            if (!Schema::hasColumn('patients', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('patients', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable();
            }
            if (!Schema::hasColumn('patients', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'occupation',
                'family_medical_history',
                'chronic_diseases',
                'medications',
                'general_health_notes',
                'smoker',
                'drinker',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_phone',
            ]);
        });
    }
};
