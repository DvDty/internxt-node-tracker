<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_type_id');
            $table->foreignId('node_id');
            $table->integer('value')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_records');
    }
};
