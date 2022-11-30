<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->integer('reputation')->nullable();
            $table->boolean('status')->nullable();
            $table->char('node_id', 40);
            $table->foreignId('address_id');
            $table->foreignId('protocol_id');
            $table->dateTime('last_seen')->nullable();
            $table->integer('port')->nullable();
            $table->string('user_agent', 100)->nullable();
            $table->double('timeout_rate', 20, 18)->nullable();
            $table->ipAddress('ip')->nullable();
            $table->boolean('space_available')->default(0)->nullable();
            $table->double('response_time', 20, 11)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique('node_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
