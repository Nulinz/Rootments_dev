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
        Schema::create('area_cluster', function (Blueprint $table) {
            $table->id();
            $table->integer('area_id')->nullable();
            $table->integer('cluster_id')->nullable();
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area_cluster', function (Blueprint $table) {
            //
        });
    }
};
