<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateComptesTable extends Migration
{
    public function up()
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(Str::uuid());
            $table->string('login');
            $table->string('password');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comptes');
    }
}
