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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('request_type');
            $table->string('reason');
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
        Schema::dropIfExists('leaves');
    }
};
