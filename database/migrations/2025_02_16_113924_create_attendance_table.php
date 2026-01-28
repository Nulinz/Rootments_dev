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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('attend_status');
            $table->string('in_location');
            $table->time('in_time');
            $table->string('out_location');
            $table->time('out_time');
            $table->string('status');
            $table->timestamp('c_on')->useCurrent(); // Stores date and time, defaults to current timestamp
            $table->timestamp('u_by')->nullable();   // Stores date and time, can be null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
