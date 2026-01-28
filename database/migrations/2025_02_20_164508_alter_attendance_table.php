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
        Schema::table('attendance', function (Blueprint $table) {
            $table->string('in_add')->nullable()->after('in_location'); // Adding the column
            $table->string('out_add')->nullable()->after('out_location'); // Adding the column
            $table->string('ot')->nullable()->after('out_add'); // Adding the column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
