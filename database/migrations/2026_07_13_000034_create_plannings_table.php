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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id('id_planning')->primary();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->enum('frequency_type', ['1x', '2x', 'perso'])->default('1x');
            $table->json('jours')->nullable()->comment("['Lun','Mer'] pour 1x/2x'"); // ['Lun','Mer'] pour 1x/2x
            $table->time('heure');
            $table->json('mesures_types')->comment("['PRESSION_ARTERIELLE','FREQUENCE_CARDIAQUE',...]"); // ['PRESSION_ARTERIELLE','FREQUENCE_CARDIAQUE',...]

            $table->enum('rappel_avant', ['24H', '2H', '1H', 'AUCUN'])->default('24H');
            $table->enum('canal', ['WHATSAPP', 'SMS'])->default('WHATSAPP');
            $table->text('notes')->nullable();

            $table->enum('status', ['ACTIF', 'INACTIF'])->default('ACTIF');

            $table->timestamps();

            $table->foreign('patient_id')->references('id_patient')->on('patients')->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('id_pharmacy')->on('pharmacy')->onDelete('cascade');

            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
