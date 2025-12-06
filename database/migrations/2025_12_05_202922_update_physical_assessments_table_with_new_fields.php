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
        Schema::table('physical_assessments', function (Blueprint $table) {
            // Bioimpedância - novos campos
            $table->decimal('bmi', 5, 2)->nullable()->after('muscle_mass_percentage')->comment('IMC calculado');
            $table->integer('body_age')->nullable()->after('bmi')->comment('Idade Corporal');
            $table->integer('metabolic_rate')->nullable()->after('body_age')->comment('Taxa Metabólica (kcal)');
            
            // Circunferências - novos campos
            $table->decimal('shoulder_measurement', 6, 2)->nullable()->after('thigh_measurement')->comment('Ombro (cm)');
            $table->decimal('forearm_measurement', 6, 2)->nullable()->after('shoulder_measurement')->comment('Antebraço (cm)');
            $table->decimal('leg_measurement', 6, 2)->nullable()->after('forearm_measurement')->comment('Perna (cm)');
            
            // Testes Cardiovasculares - novo campo
            $table->integer('post_exercise_heart_rate')->nullable()->after('max_heart_rate')->comment('FC Pós Exercício (bpm)');
            
            // Avaliação Postural - campo para armazenar JSON com paths das fotos
            $table->json('postural_photos')->nullable()->after('recommendations')->comment('Fotos da avaliação postural');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('physical_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'bmi',
                'body_age',
                'metabolic_rate',
                'shoulder_measurement',
                'forearm_measurement',
                'leg_measurement',
                'post_exercise_heart_rate',
                'postural_photos',
            ]);
        });
    }
};
