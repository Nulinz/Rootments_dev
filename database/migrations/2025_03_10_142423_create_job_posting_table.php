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
        Schema::create('job_posting', function (Blueprint $table) {
            $table->id();
            $table->integer('rec_id')->nullable();
            $table->string('job_title')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('job_type')->nullable();
            $table->string('job_desc')->nullable();
            $table->string('hrs')->nullable();
            $table->string('salary')->nullable();
            $table->string('benefits')->nullable();
            $table->date('post_date')->nullable();
            $table->integer('req_to')->nullable();
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
        Schema::dropIfExists('job_posting');
    }
};
