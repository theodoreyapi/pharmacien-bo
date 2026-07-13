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
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id('id_rendez_vous')->primary();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('planning_id')->nullable(); // null = RDV ponctuel

            $table->date('date');
            $table->time('heure');
            $table->json('mesures_types');

            $table->enum('status', ['ATTENTE', 'EFFECTUE', 'MANQUE'])->default('ATTENTE');
            $table->unsignedBigInteger('mesure_liee_id')->nullable(); // si mesure liée après passage
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('patient_id')->references('id_patient')->on('patients')->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('id_pharmacy')->on('pharmacy')->onDelete('cascade');
            $table->foreign('planning_id')->references('id_planning')->on('plannings')->onDelete('cascade');

            $table->index(['patient_id', 'status']);
            $table->index(['patient_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
