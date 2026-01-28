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
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('emp_name');
            $table->integer('store_id');
            $table->date('res_date');
            $table->string('res_reason');
            $table->string('request_to')->nullable();
            $table->string('esculate_to')->nullable();
            $table->string('request_status')->nullable();
            $table->string('esculate_status')->nullable();
            $table->string('esculate_status')->nullable();
            $table->string('status')->default('Pending')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resignations');
    }
};
