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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon')->nullable(); // Icon name or emoji
            $table->string('badge_color')->default('blue'); // Color for badge display
            $table->enum('type', ['classes', 'streak', 'workouts', 'assessments', 'custom'])->default('custom');
            $table->integer('required_value')->nullable(); // Required value to unlock (e.g., 10 classes)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
