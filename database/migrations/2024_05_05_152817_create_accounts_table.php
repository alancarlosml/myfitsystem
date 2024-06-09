<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establishment_id');
            $table->foreign('establishment_id')->references('id')->on('establishments')->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 8, 2);
            $table->enum('type', ['receber', 'pagar']); // Tipo de conta: a receber ou a pagar
            $table->date('payment_date');
            $table->enum('payment_type', ['credito', 'debito', 'pix','boleto', 'dinheiro']);
            $table->date('due_date');
            $table->boolean('paid')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}