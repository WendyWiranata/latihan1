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
       Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('company_id');
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('employee_id')->unique();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone');
    $table->string('address');
    $table->string('department');
    $table->string('position');
    $table->date('join_date');
    $table->timestamps();

    $table->foreign('company_id')->references('id')->on('companies');
    $table->foreign('user_id')->references('id')->on('users');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
