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
        Schema::create('attd_ot', function (Blueprint $table) {
            $table->id();
            $table->integer('attd_id')->nullable();
            $table->string('cat')->nullable();
            $table->string('time')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attd_ot', function (Blueprint $table) {
            //
        });
    }
};
