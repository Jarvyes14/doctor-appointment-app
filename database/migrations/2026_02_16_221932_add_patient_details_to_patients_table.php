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
            // Datos Personales
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro'])->nullable();
            $table->string('occupation')->nullable();

            // Antecedentes
            $table->text('family_medical_history')->nullable();
            $table->text('chronic_diseases')->nullable();
            $table->text('medications')->nullable();

            // Información General
            $table->text('general_health_notes')->nullable();
            $table->boolean('smoker')->default(false);
            $table->boolean('drinker')->default(false);

            // Contacto de Emergencia
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
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
