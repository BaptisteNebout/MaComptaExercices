<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcrituresTable extends Migration
{
    public function up()
    {
        Schema::create('écritures', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('compte_uuid');
            $table->string('label')->default('');
            $table->date('date')->nullable();
            $table->enum('type', ['C', 'D']);
            $table->double('amount', 14, 2)->default(0.00);
            $table->timestamps();
            
            $table->foreign('compte_uuid')->references('uuid')->on('comptes')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('écritures');
    }
}