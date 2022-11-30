<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->integer('reputation')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->string('email', 200)->nullable();
            $table->timestamps();

            $table->unique('ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
