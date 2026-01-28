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
        Schema::create('work_update', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->nullable();
            $table->string('b_ftd',25)->nullable();
            $table->string('b_mtd',25)->nullable();
            $table->string('b_ly',25)->nullable();
            $table->string('b_ltl',25)->nullable();
            $table->string('q_ftd',25)->nullable();
            $table->string('q_mtd',25)->nullable();
            $table->string('q_ly',25)->nullable();
            $table->string('q_ltl',25)->nullable();
            $table->string('w_ftd',25)->nullable();
            $table->string('w_mtd',25)->nullable();
            $table->string('w_ly',25)->nullable();
            $table->string('w_ltl',25)->nullable();
            $table->string('los_ftd',25)->nullable();
            $table->string('los_mtd',25)->nullable();
            $table->string('los_abs',25)->nullable();
            $table->string('abs_ftd',25)->nullable();
            $table->string('abs_tgt',25)->nullable();
            $table->string('abs_ach',25)->nullable();
            $table->string('abs_per',25)->nullable();
            $table->string('con_per',25)->nullable();
            $table->string('status',25)->default('Active')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_update');
    }
};
