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
        Schema::table('student_contracts', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'pago', 'vencido'])->default('pendente')->after('active');
            $table->dateTime('paid_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_contracts', function (Blueprint $table) {
            $table->dropColumn(['status', 'paid_at']);
        });
    }
};
