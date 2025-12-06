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
        Schema::table('modalities', function (Blueprint $table) {
            // Adicionar establishment_id se não existir
            if (!Schema::hasColumn('modalities', 'establishment_id')) {
                $table->unsignedBigInteger('establishment_id')->nullable()->after('id');
                $table->foreign('establishment_id')->references('id')->on('establishments')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalities', function (Blueprint $table) {
            if (Schema::hasColumn('modalities', 'establishment_id')) {
                $table->dropForeign(['establishment_id']);
                $table->dropColumn('establishment_id');
            }
        });
    }
};
