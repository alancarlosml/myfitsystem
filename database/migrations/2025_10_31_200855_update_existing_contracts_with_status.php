<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update establishment contracts
        // If payment_date is in the past, mark as 'pago' (assuming old system marked them as paid)
        // Otherwise keep as 'pendente'
        DB::table('establishment_contracts')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update([
                'status' => DB::raw("CASE 
                    WHEN payment_date < NOW() AND active = 1 THEN 'pago'
                    WHEN payment_date < NOW() AND active = 0 THEN 'vencido'
                    ELSE 'pendente'
                END"),
                'paid_at' => DB::raw("CASE 
                    WHEN payment_date < NOW() AND active = 1 THEN payment_date
                    ELSE NULL
                END")
            ]);

        // Update student contracts
        DB::table('student_contracts')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update([
                'status' => DB::raw("CASE 
                    WHEN payment_date < NOW() AND active = 1 THEN 'pago'
                    WHEN payment_date < NOW() AND active = 0 THEN 'vencido'
                    ELSE 'pendente'
                END"),
                'paid_at' => DB::raw("CASE 
                    WHEN payment_date < NOW() AND active = 1 THEN payment_date
                    ELSE NULL
                END")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only updates data, no schema changes to reverse
    }
};
