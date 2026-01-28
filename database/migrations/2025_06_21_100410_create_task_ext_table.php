<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_ext', function (Blueprint $table) {
            $table->id();
            $table->string('tas_id')->nullable();
            $table->string('request_for')->nullable();
            $table->string('extend_date')->nullable();
            $table->string('c_remakrs')->nullable();
            $table->string('a_remakrs')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_ext');
    }
};
