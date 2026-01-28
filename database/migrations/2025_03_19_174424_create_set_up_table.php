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
        Schema::create('set_up', function (Blueprint $table) {
            $table->id();
            $table->string('st_code')->nullable();
            $table->string('st_name')->nullable();
            $table->string('st_add')->nullable();
            $table->string('st_city')->nullable();
            $table->string('st_state')->nullable();
            $table->string('st_pin')->nullable();
            $table->string('st_loc')->nullable();
            $table->string('status')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_up');
    }
};
