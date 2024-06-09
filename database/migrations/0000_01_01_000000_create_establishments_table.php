<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentsTable extends Migration
{
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['academia', 'crossfit', 'personal_trainer']);
            $table->string('owner');
            $table->string('cnpj')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('social_network')->nullable();
            $table->string('website')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('establishments');
    }
}
