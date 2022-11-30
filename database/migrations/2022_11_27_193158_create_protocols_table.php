<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();

            $table->unique('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('protocols');
    }
};
