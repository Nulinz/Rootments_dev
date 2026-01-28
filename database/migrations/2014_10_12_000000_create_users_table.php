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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_no')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('emp_code')->unique();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('dept')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('store_id')->nullable();
            $table->string('qulification')->nullable();
            $table->string('job_tittle')->nullable();
            $table->string('job_type')->nullable();
            $table->string('exprience')->nullable();
            $table->string('pro_skill')->nullable();
            $table->date('pre_start_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_holder_name')->nullable();
            $table->string('ac_no')->nullable();
            $table->string('ifcs_code')->nullable();
            $table->string('acount_type')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('base_salary')->nullable();
            $table->string('house_rent_allowance')->nullable();
            $table->string('conveyance')->nullable();
            $table->string('medical')->nullable();
            $table->string('speical')->nullable();
            $table->string('other')->nullable();
            $table->string('pro_fund')->nullable();
            $table->string('emp_state_insurance')->nullable();
            $table->string('profession_tax')->nullable();
            $table->string('income_tax')->nullable();
            $table->string('performance_bonus')->nullable();
            $table->string('net_salary')->nullable();
            $table->string('aadhar_img')->nullable();
            $table->string('agreement')->nullable();
            $table->boolean('status')->default(1)->comment('1->active, 2->inactive');
            $table->string('device_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
