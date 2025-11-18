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
        // No MySQL, precisamos remover a constraint antes de alterar a coluna
        DB::statement('ALTER TABLE role_user DROP FOREIGN KEY role_user_establishment_id_foreign');
        
        Schema::table('role_user', function (Blueprint $table) {
            // Tornar a coluna nullable
            $table->unsignedBigInteger('establishment_id')->nullable()->change();
        });
        
        // Recriar a foreign key permitindo null
        DB::statement('ALTER TABLE role_user ADD CONSTRAINT role_user_establishment_id_foreign 
                       FOREIGN KEY (establishment_id) REFERENCES establishments(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover a constraint
        DB::statement('ALTER TABLE role_user DROP FOREIGN KEY role_user_establishment_id_foreign');
        
        Schema::table('role_user', function (Blueprint $table) {
            // Tornar a coluna NOT NULL novamente (pode causar erro se houver nulls)
            $table->unsignedBigInteger('establishment_id')->nullable(false)->change();
        });
        
        // Recriar a foreign key sem nullable
        DB::statement('ALTER TABLE role_user ADD CONSTRAINT role_user_establishment_id_foreign 
                       FOREIGN KEY (establishment_id) REFERENCES establishments(id) ON DELETE CASCADE');
    }
};