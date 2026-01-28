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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->boolean('role');
            $table->string('module_name');
            $table->boolean('permission_view')->default(0);
            $table->boolean('permission_add')->default(0);
            $table->boolean('permission_edit')->default(0);
            $table->boolean('permission_delete')->default(0);
            $table->boolean('permission_requestto')->default(0);
            $table->boolean('permission_esulateto')->default(0);
            $table->boolean('permission_status')->default(0);
            $table->boolean('permission_show')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
