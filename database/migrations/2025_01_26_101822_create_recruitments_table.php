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
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('dept')->nullable();
            $table->integer('role')->nullable();
            $table->string('loc')->nullable();
            $table->date('res_date');
            $table->integer('vacancy')->nullable();
            $table->string('request_to')->nullable();
            $table->string('exp')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('Pending')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
