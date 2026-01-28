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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_title');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('task_description')->nullable();
            $table->string('additional_info')->nullable();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High'])->nullable();
            $table->string('task_file')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1->active, 2->inactive');
            $table->enum('task_status', ['To Do', 'In Progress', 'On Hold', 'Completed'])->default('To Do')->comment('Task status options: To Do, In Progress, On Hold, Completed');
            $table->string('assign_by');
            $table->string('tasks_completed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
