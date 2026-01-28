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
        Schema::create('resign_list', function (Blueprint $table) {
            $table->id();
            $table->integer('res_id')->nullable();
            $table->string('formality')->nullable();
            $table->text('review')->nullable();
            $table->string('file')->nullable();
            $table->integer('c_by')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resign_list');
    }
};
