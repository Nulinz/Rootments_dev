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
        Schema::create('maintain_req', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('cat')->nullable();
            $table->integer('sub')->nullable();
            $table->date('req_date')->nullable();
            $table->text('desp')->nullable();
            $table->text('file')->nullable();
            $table->integer('req_to')->nullable();
            $table->integer('esculate_to')->nullable();
            $table->string('req_status')->nullable();
            $table->string('esculate_status')->nullable();
            $table->string('status')->nullable();
            $table->integer('task_id')->nullable();
            $table->string('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintain_req');
    }
};
