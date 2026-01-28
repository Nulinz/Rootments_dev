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
        Schema::create('e_setup', function (Blueprint $table) {
            $table->id();
            $table->integer('set_id')->nullable();
            $table->string('cat')->nullable();
            $table->string('sub')->nullable();
            $table->text('remark')->nullable();
            $table->text('file')->nullable();
            $table->string('status')->nullable();
            $table->text('s_remark')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_setup');
    }
};
