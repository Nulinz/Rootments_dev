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
        Schema::create('walkin', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->date('f_date')->nullable();
            $table->string('walk_status')->nullable();
            $table->string('cat')->nullable();
            $table->string('sub')->nullable();
            $table->text('remark')->nullable();
            $table->string('status')->default(1)->nullable();
            $table->string('manager')->nullable();
            $table->string('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin');
    }
};
