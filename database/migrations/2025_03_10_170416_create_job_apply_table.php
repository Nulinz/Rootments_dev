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
        Schema::create('job_apply', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->string('name')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('add')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('edu')->nullable();
            $table->string('work_exp')->nullable();
            $table->string('skill')->nullable();
            $table->string('notice')->nullable();
            $table->string('certify')->nullable();
            $table->string('resume')->nullable();
            $table->string('status')->default('applied')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_apply');
    }
};
