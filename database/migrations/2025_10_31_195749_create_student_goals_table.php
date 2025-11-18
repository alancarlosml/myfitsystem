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
        Schema::create('student_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('establishment_id')->nullable();
            $table->string('goal_type'); // weekly_classes, monthly_classes, weight_loss, etc
            $table->string('goal_name'); // Nome descritivo da meta
            $table->decimal('target_value', 10, 2); // Valor alvo
            $table->decimal('current_value', 10, 2)->default(0); // Valor atual
            $table->string('unit')->nullable(); // kg, classes, etc
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('achieved')->default(false);
            $table->timestamp('achieved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('establishment_id')->references('id')->on('establishments')->onDelete('set null');
            
            $table->index(['student_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_goals');
    }
};
