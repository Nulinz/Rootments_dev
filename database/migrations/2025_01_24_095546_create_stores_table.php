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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_code');
            $table->string('store_name');
            $table->string('store_mail')->nullable();
            $table->string('store_contact');
            $table->string('store_alt_contact')->nullable();
            $table->time('store_start_time');
            $table->time('store_end_time');
            $table->string('store_address');
            $table->integer('store_pincode');
            $table->string('store_geo');
            $table->tinyInteger('status')->default(1)->comment('1->active, 2->inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
