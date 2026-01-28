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
        Schema::create('m_salary', function (Blueprint $table) {
            $table->id();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('store')->nullable();
            $table->integer('emp_id')->nullable();
            $table->integer('salary')->nullable();
            $table->integer('total_work')->nullable();
            $table->integer('present')->nullable();
            $table->integer('lop')->nullable();
            $table->integer('incentive')->nullable();
            $table->integer('ot')->nullable();
            $table->integer('deduct')->nullable();
            $table->integer('bonus')->nullable();
            $table->integer('advance')->nullable();
            $table->integer('total')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_salary');
    }
};
